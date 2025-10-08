<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GeneratePageSeederCommand extends Command
{
    protected $signature = 'seeder:pages 
                            {name? : Name of the seeder (default: PagesSeeder)} 
                            {--no-images : Skip copying images to seeder directory}
                            {--output-path= : Custom output path for seeder file}';

    protected $description = 'Generate a seeder for pages with their images';

    protected $seederName;
    protected $seederPath;
    protected $imagesPath;
    protected $copiedImages;
    protected $imageMapping = [];

    public function handle()
    {
        $this->info('📦 Génération du seeder pour les pages...');
        
        // Configuration
        $this->copiedImages = collect();
        $this->seederName = $this->argument('name') ?: 'PagesSeeder';
        $this->setupPaths();
        
        // Récupération des pages
        $pages = Page::all();
        
        if ($pages->isEmpty()) {
            $this->warn('Aucune page trouvée dans la base de données.');
            return 0;
        }

        $this->info("📄 {$pages->count()} pages trouvées");

        // Traitement des images par défaut (sauf si --no-images)
        if (!$this->option('no-images')) {
            $this->createImagesDirectory();
            $this->processImages($pages);
        } else {
            $this->info('⏭️  Traitement des images ignoré (--no-images)');
        }

        // Génération du seeder
        $this->generateSeederFile($pages);

        // Résumé
        $this->displaySummary();

        return 0;
    }

    /**
     * Configure les chemins de sortie
     */
    protected function setupPaths(): void
    {
        $this->seederPath = $this->option('output-path') 
            ? $this->option('output-path')
            : database_path('seeders');

        $this->imagesPath = database_path("seeders/images/" . Str::snake($this->seederName));
    }

    /**
     * Crée le répertoire pour les images
     */
    protected function createImagesDirectory(): void
    {
        if (!File::exists($this->imagesPath)) {
            File::makeDirectory($this->imagesPath, 0755, true);
            $this->info("📁 Répertoire créé : {$this->imagesPath}");
        }
    }

    /**
     * Traite toutes les images des pages
     */
    protected function processImages($pages): void
    {
        $this->info('🖼️  Traitement des images...');
        
        foreach ($pages as $page) {
            $this->processPageImages($page);
        }

        $this->info("📋 {$this->copiedImages->count()} images copiées");
    }

    /**
     * Traite les images d'une page spécifique
     */
    protected function processPageImages(Page $page): void
    {
        $imageFiles = collect();
        
        // Extraire les images depuis contents
        if (!empty($page->contents) && is_array($page->contents)) {
            $this->extractImagesFromArray($page->contents, $imageFiles);
        }

        // Copier les images trouvées
        foreach ($imageFiles->unique() as $imagePath) {
            $this->copyImageFile($imagePath);
        }
    }

    /**
     * Extrait récursivement les images d'un tableau
     */
    protected function extractImagesFromArray(array $data, $imageFiles): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->extractImagesFromArray($value, $imageFiles);
            } elseif (is_string($value) && $this->looksLikeImagePath($value)) {
                $imageFiles->push($value);
            }
        }
    }

    /**
     * Détermine si une chaîne est un chemin d'image
     */
    protected function looksLikeImagePath(string $value): bool
    {
        // Vérifie les extensions d'images communes
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = pathinfo($value, PATHINFO_EXTENSION);
        
        return in_array(strtolower($extension), $imageExtensions) 
            && (str_contains($value, 'images-bg/') || str_contains($value, 'image-photos/'));
    }

    /**
     * Copie un fichier image vers le répertoire seeder
     */
    protected function copyImageFile(string $imagePath): void
    {
        $disk = Storage::disk('public');
        
        if (!$disk->exists($imagePath)) {
            $this->warn("⚠️  Image non trouvée : {$imagePath}");
            return;
        }

        // Génère un nouveau nom pour éviter les conflits
        $originalName = basename($imagePath);
        $newName = $this->generateUniqueImageName($originalName);
        $destinationPath = $this->imagesPath . '/' . $newName;

        // Copie le fichier
        $sourceFullPath = $disk->path($imagePath);
        File::copy($sourceFullPath, $destinationPath);

        // Enregistre le mapping
        $this->imageMapping[$imagePath] = $newName;
        $this->copiedImages->push($imagePath);

        if ($this->output->isVerbose()) {
            $this->line("   📄 {$imagePath} → {$newName}");
        }
    }

    /**
     * Génère un nom unique pour l'image
     */
    protected function generateUniqueImageName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $counter = 1;
        $newName = $originalName;

        while (File::exists($this->imagesPath . '/' . $newName)) {
            $newName = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $newName;
    }

    /**
     * Génère le fichier seeder
     */
    protected function generateSeederFile($pages): void
    {
        $this->info('📝 Génération du fichier seeder...');

        $seederContent = $this->generateSeederContent($pages);
        $filePath = $this->seederPath . '/' . $this->seederName . '.php';

        File::put($filePath, $seederContent);

        $this->info("✅ Seeder généré : {$filePath}");
    }

    /**
     * Génère le contenu du seeder
     */
    protected function generateSeederContent($pages): string
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $pagesData = $this->preparePagesData($pages);
        
        $stub = '<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class {{SEEDER_NAME}} extends Seeder
{
    /**
     * Run the database seeds.
     * Generated on {{TIMESTAMP}}
     */
    public function run(): void
    {
        // Copier les images depuis le répertoire seeder vers storage/app/public
        $this->copyImages();
        
        // Insérer les pages
        $this->seedPages();
    }

    /**
     * Copie les images du seeder vers le storage public
     */
    protected function copyImages(): void
    {
        $seederImagesPath = database_path(\'seeders/images/{{SEEDER_SNAKE_NAME}}\');
        $disk = Storage::disk(\'public\');
        
        if (!File::exists($seederImagesPath)) {
            return;
        }

        $imageFiles = File::allFiles($seederImagesPath);
        
        foreach ($imageFiles as $file) {
            $fileName = $file->getFilename();
            $this->copyImageToStorage($fileName, $file->getPathname(), $disk);
        }
    }

    /**
     * Copie une image vers le bon répertoire de storage
     */
    protected function copyImageToStorage(string $fileName, string $sourcePath, $disk): void
    {
        // Mapping des fichiers vers leurs répertoires d\'origine
        $imageMapping = {{IMAGE_MAPPING}};
        
        if (isset($imageMapping[$fileName])) {
            $targetPath = $imageMapping[$fileName];
            $targetDir = dirname($targetPath);
            
            // Créer le répertoire si nécessaire
            if (!$disk->exists($targetDir)) {
                $disk->makeDirectory($targetDir);
            }
            
            // Copier le fichier
            $disk->put($targetPath, File::get($sourcePath));
        }
    }

    /**
     * Insert les pages dans la base de données
     */
    protected function seedPages(): void
    {
        $pages = {{PAGES_DATA}};
        
        foreach ($pages as $page) {
            DB::table(\'cms_pages\')->insert($page);
        }
    }
}';

        return str_replace([
            '{{SEEDER_NAME}}',
            '{{SEEDER_SNAKE_NAME}}',
            '{{TIMESTAMP}}',
            '{{IMAGE_MAPPING}}',
            '{{PAGES_DATA}}'
        ], [
            $this->seederName,
            Str::snake($this->seederName),
            $timestamp,
            $this->formatImageMapping(),
            $this->formatPagesData($pagesData)
        ], $stub);
    }

    /**
     * Prépare les données des pages pour le seeder
     */
    protected function preparePagesData($pages): array
    {
        return $pages->map(function ($page) {
            $data = $page->toArray();
            
            // Convertir les timestamps
            $data['created_at'] = $page->created_at?->format('Y-m-d H:i:s');
            $data['updated_at'] = $page->updated_at?->format('Y-m-d H:i:s');
            $data['published_at'] = $page->published_at?->format('Y-m-d H:i:s');
            
            // Remplacer les chemins d\'images dans contents si nécessaire
            if (!$this->option('no-images') && !empty($data['contents'])) {
                $data['contents'] = $this->updateImagePathsInContents($data['contents']);
            }
            
            // Convertir les arrays en JSON pour l'insertion
            if (is_array($data['contents'])) {
                $data['contents'] = json_encode($data['contents']);
            }
            if (is_array($data['meta_data'])) {
                $data['meta_data'] = json_encode($data['meta_data']);
            }
            if (is_array($data['statics'])) {
                $data['statics'] = json_encode($data['statics']);
            }
            
            // Supprimer l\'ID pour permettre l\'auto-increment
            unset($data['id']);
            
            return $data;
        })->toArray();
    }

    /**
     * Met à jour les chemins d\'images dans le contenu
     */
    protected function updateImagePathsInContents(array $contents): array
    {
        $updatedContents = $contents;
        
        foreach ($this->imageMapping as $originalPath => $newName) {
            $updatedContents = $this->replaceInArray($updatedContents, $originalPath, $originalPath);
        }
        
        return $updatedContents;
    }

    /**
     * Remplace récursivement dans un tableau
     */
    protected function replaceInArray(array $array, string $search, string $replace): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->replaceInArray($value, $search, $replace);
            } elseif (is_string($value)) {
                $array[$key] = str_replace($search, $replace, $value);
            }
        }
        
        return $array;
    }

    /**
     * Formate le mapping des images pour le seeder
     */
    protected function formatImageMapping(): string
    {
        if (empty($this->imageMapping)) {
            return '[]';
        }

        $mapping = [];
        foreach ($this->imageMapping as $originalPath => $newName) {
            $mapping[$newName] = $originalPath;
        }

        return var_export($mapping, true);
    }

    /**
     * Formate les données des pages pour le seeder
     */
    protected function formatPagesData(array $pagesData): string
    {
        return var_export($pagesData, true);
    }

    /**
     * Affiche le résumé des opérations
     */
    protected function displaySummary(): void
    {
        $this->newLine();
        $this->info('📊 Résumé :');
        $this->line("   • Fichier seeder : {$this->seederPath}/{$this->seederName}.php");
        
        if (!$this->option('no-images')) {
            $this->line("   • Images copiées : " . count($this->copiedImages));
            $this->line("   • Répertoire images : {$this->imagesPath}");
        } else {
            $this->line("   • Images ignorées (--no-images)");
        }
        
        $this->newLine();
        $this->info('🎯 Pour utiliser ce seeder :');
        $this->line("   php artisan db:seed --class={$this->seederName}");
    }
}