<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Page;
use App\Settings\AdminSettings;

class CleanOrphanedFilesCommand extends Command
{
    protected $signature = 'files:clean-orphaned 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--detailed : Show detailed output}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Clean orphaned files by comparing database records with filesystem';

    protected $totalFilesScanned = 0;
    protected $totalFilesDeleted = 0;
    protected $totalDirectoriesDeleted = 0;

    public function handle()
    {
        $this->info('🧹 Nettoyage des fichiers orphelins...');
        
        if (!$this->option('force') && !$this->option('dry-run')) {
            if (!$this->confirm('Êtes-vous sûr de vouloir nettoyer les fichiers orphelins ? Cette action est irréversible.')) {
                $this->info('Opération annulée.');
                return 0;
            }
        }

        $modelsConfig = $this->getModelsConfiguration();
        $allUsedFiles = collect();

        // Collecte tous les fichiers utilisés
        foreach ($modelsConfig as $config) {
            $usedFiles = $this->extractUsedFiles($config);
            $allUsedFiles = $allUsedFiles->merge($usedFiles);
            
            if ($this->option('detailed')) {
                $this->info("✓ Analysé {$config['model']} : " . $usedFiles->count() . " fichiers trouvés");
            }
        }

        // Nettoie chaque répertoire
        foreach ($modelsConfig as $config) {
            foreach ($config['directories'] as $directory) {
                $this->cleanDirectory($directory, $allUsedFiles);
            }
        }

        // Résumé
        $this->newLine();
        $this->info("📊 Résumé :");
        $this->line("   • Fichiers analysés : {$this->totalFilesScanned}");
        
        if ($this->option('dry-run')) {
            $this->warn("   • Fichiers qui seraient supprimés : {$this->totalFilesDeleted}");
            $this->warn("   • Dossiers qui seraient supprimés : {$this->totalDirectoriesDeleted}");
            $this->newLine();
            $this->info("🔍 Mode test activé - Aucun fichier n'a été supprimé");
        } else {
            $this->error("   • Fichiers supprimés : {$this->totalFilesDeleted}");
            $this->error("   • Dossiers supprimés : {$this->totalDirectoriesDeleted}");
            $this->newLine();
            $this->info("✅ Nettoyage terminé !");
        }

        return 0;
    }

    /**
     * Configuration des modèles à analyser
     */
    protected function getModelsConfiguration(): array
    {
        return [
            [
                'name' => 'Pages',
                'model' => Page::class,
                'fields' => ['contents'], // Champs contenant des références de fichiers
                'directories' => ['images-bg', 'image-photos'], // Répertoires à nettoyer
                'extractor' => 'extractFromPageContents' // Méthode pour extraire les fichiers
            ],
            [
                'name' => 'AdminSettings', 
                'model' => AdminSettings::class,
                'fields' => ['logo'],
                'directories' => ['logos'],
                'extractor' => 'extractFromSettings'
            ]
        ];
    }

    /**
     * Extrait les fichiers utilisés d'une configuration de modèle
     */
    protected function extractUsedFiles(array $config): \Illuminate\Support\Collection
    {
        $method = $config['extractor'];
        return $this->$method($config);
    }

    /**
     * Extrait les chemins de fichiers depuis le contenu des pages
     */
    protected function extractFromPageContents(array $config): \Illuminate\Support\Collection
    {
        $model = $config['model'];
        $usedFiles = collect();

        $model::all()->each(function ($page) use (&$usedFiles) {
            if (!empty($page->contents) && is_array($page->contents)) {
                $this->extractFilesFromArray($page->contents, $usedFiles);
            }
        });

        return $usedFiles->filter()->unique();
    }

    /**
     * Extrait les chemins de fichiers depuis les paramètres admin
     */
    protected function extractFromSettings(array $config): \Illuminate\Support\Collection
    {
        $usedFiles = collect();
        
        try {
            $settings = app(AdminSettings::class);
            if (!empty($settings->logo)) {
                $usedFiles->push($settings->logo);
            }
        } catch (\Exception $e) {
            $this->warn("Impossible de charger les paramètres admin : " . $e->getMessage());
        }

        return $usedFiles->filter()->unique();
    }

    /**
     * Extrait récursivement les chemins de fichiers d'un tableau
     */
    protected function extractFilesFromArray(array $data, \Illuminate\Support\Collection &$files): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->extractFilesFromArray($value, $files);
            } elseif (is_string($value)) {
                // Cherche les patterns de fichiers (commence par un répertoire connu)
                if ($this->looksLikeFilePath($value)) {
                    $files->push($value);
                }
            }
        }
    }

    /**
     * Détermine si une chaîne ressemble à un chemin de fichier
     */
    protected function looksLikeFilePath(string $value): bool
    {
        // Patterns de répertoires connus
        $knownDirectories = ['images-bg/', 'image-photos/', 'logos/'];
        
        foreach ($knownDirectories as $dir) {
            if (str_starts_with($value, $dir)) {
                return true;
            }
        }

        // Pattern générique pour les extensions d'images
        return preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $value);
    }

    /**
     * Nettoie un répertoire spécifique
     */
    protected function cleanDirectory(string $directory, \Illuminate\Support\Collection $usedFiles): void
    {
        $disk = Storage::disk('public');
        
        if (!$disk->exists($directory)) {
            if ($this->option('detailed')) {
                $this->warn("⚠️  Répertoire inexistant : {$directory}");
            }
            return;
        }

        $this->info("🔍 Analyse du répertoire : {$directory}");

        $allFiles = collect($disk->allFiles($directory));
        $this->totalFilesScanned += $allFiles->count();

        $orphanedFiles = $allFiles->filter(function ($file) use ($usedFiles) {
            return !$usedFiles->contains($file);
        });

        if ($orphanedFiles->isEmpty()) {
            $this->info("   ✅ Aucun fichier orphelin trouvé");
            return;
        }

        foreach ($orphanedFiles as $file) {
            $this->totalFilesDeleted++;
            
            if ($this->option('dry-run')) {
                $this->line("   🗑️  [DRY-RUN] Supprimerait : {$file}");
            } else {
                $disk->delete($file);
                $this->line("   🗑️  Supprimé : {$file}");
            }
        }

        // Nettoie les dossiers vides
        $this->cleanEmptyDirectories($directory, $disk);
    }

    /**
     * Supprime les répertoires vides récursivement
     */
    protected function cleanEmptyDirectories(string $baseDirectory, $disk): void
    {
        $directories = collect($disk->allDirectories($baseDirectory))
            ->sortByDesc('length'); // Trie par profondeur décroissante

        foreach ($directories as $dir) {
            $files = $disk->allFiles($dir);
            $subdirs = $disk->directories($dir);

            if (empty($files) && empty($subdirs)) {
                $this->totalDirectoriesDeleted++;
                
                if ($this->option('dry-run')) {
                    $this->line("   📁 [DRY-RUN] Supprimerait le dossier vide : {$dir}");
                } else {
                    $disk->deleteDirectory($dir);
                    $this->line("   📁 Dossier vide supprimé : {$dir}");
                }
            }
        }
    }
}