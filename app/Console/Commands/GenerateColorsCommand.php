<?php

namespace App\Console\Commands;

use App\Services\ColorPaletteService;
use Illuminate\Console\Command;

class GenerateColorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:colors 
                            {--primary= : Couleur primaire (ex: #B24030)}
                            {--secondary= : Couleur secondaire (ex: #F7D463 ou "auto" pour génération automatique)}
                            {--tertiary= : Couleur tertiaire optionnelle (ex: #10B981)}
                            {--no-tertiary : Ne pas demander de couleur tertiaire en mode interactif}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère automatiquement les palettes de couleurs pour le projet et met à jour les fichiers CSS';

    /**
     * Service de gestion des palettes de couleurs
     */
    protected ColorPaletteService $colorService;

    /**
     * Fichiers CSS à mettre à jour
     */
    protected array $cssFiles = [
        'resources/css/front/front.css',
        'resources/css/filament/admin/filament.css',
    ];

    public function __construct(ColorPaletteService $colorService)
    {
        parent::__construct();
        $this->colorService = $colorService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎨 Générateur de palettes de couleurs X-Artfil');
        $this->newLine();

        // Récupération ou demande de la couleur primaire
        $primaryColor = $this->getPrimaryColor();
        if (!$primaryColor) {
            $this->error('Couleur primaire invalide. Veuillez utiliser un format hex valide (ex: #B24030)');
            return Command::FAILURE;
        }

        // Récupération ou demande de la couleur secondaire
        $secondaryColor = $this->getSecondaryColor($primaryColor);
        if (!$secondaryColor) {
            $this->error('Couleur secondaire invalide. Veuillez utiliser un format hex valide (ex: #F7D463)');
            return Command::FAILURE;
        }

        // Récupération ou demande de la couleur tertiaire
        $tertiaryColor = $this->getTertiaryColor();

        $this->info("Couleur primaire: {$primaryColor}");
        $this->info("Couleur secondaire: {$secondaryColor}");
        if ($tertiaryColor) {
            $this->info("Couleur tertiaire: {$tertiaryColor}");
        } else {
            $this->info("Couleur tertiaire: non définie");
        }
        $this->newLine();

        // Génération des palettes
        $this->info('Génération des palettes de couleurs...');
        $primaryPalette = $this->colorService->generatePalette($primaryColor);
        $secondaryPalette = $this->colorService->generatePalette($secondaryColor);
        $tertiaryPalette = $tertiaryColor ? $this->colorService->generatePalette($tertiaryColor) : null;

        // Affichage des palettes générées
        $this->displayPalette('Primaire', $primaryPalette);
        $this->displayPalette('Secondaire', $secondaryPalette);
        if ($tertiaryPalette) {
            $this->displayPalette('Tertiaire', $tertiaryPalette);
        }

        // Mise à jour des fichiers CSS
        $this->info('Mise à jour des fichiers CSS...');
        $updatedFiles = 0;
        
        foreach ($this->cssFiles as $cssFile) {
            $fullPath = base_path($cssFile);
            
            if ($this->colorService->updateCssFile($fullPath, $primaryPalette, $secondaryPalette, $tertiaryPalette)) {
                $this->line("✅ {$cssFile}");
                $updatedFiles++;
            } else {
                $this->line("❌ {$cssFile} (fichier introuvable ou balises manquantes)");
            }
        }

        // Mise à jour du provider Filament
        $this->info('Mise à jour du provider Filament...');
        if ($this->colorService->updateFilamentProvider($primaryColor, $secondaryColor)) {
            $this->line("✅ app/Providers/Filament/AdminPanelProvider.php");
        } else {
            $this->line("❌ app/Providers/Filament/AdminPanelProvider.php (fichier introuvable ou structure incorrecte)");
        }

        $this->newLine();
        $this->info("🎉 Génération terminée ! {$updatedFiles} fichier(s) CSS mis à jour.");
        $this->comment('N\'oubliez pas de recompiler vos assets (npm run build)');

        return Command::SUCCESS;
    }

    /**
     * Récupère la couleur primaire depuis l'option ou demande à l'utilisateur
     */
    protected function getPrimaryColor(): ?string
    {
        $primary = $this->option('primary');
        
        if (!$primary) {
            $primary = $this->ask('Couleur primaire (format hex, ex: #B24030)');
        }

        if (!$primary) {
            return null;
        }

        $primary = $this->colorService->normalizeHexColor($primary);
        
        return $this->colorService->validateHexColor($primary) ? $primary : null;
    }

    /**
     * Récupère la couleur secondaire depuis l'option ou demande à l'utilisateur
     */
    protected function getSecondaryColor(string $primaryColor): ?string
    {
        $secondary = $this->option('secondary');
        
        if (!$secondary) {
            $secondary = $this->ask('Couleur secondaire (format hex ex: #F7D463, ou appuyez sur Entrée pour génération automatique)', 'auto');
        }

        if ($secondary === 'auto' || $secondary === '') {
            $this->info('Génération automatique de la couleur secondaire...');
            return $this->colorService->generateComplementaryColor($primaryColor);
        }

        $secondary = $this->colorService->normalizeHexColor($secondary);
        
        return $this->colorService->validateHexColor($secondary) ? $secondary : null;
    }

    /**
     * Récupère la couleur tertiaire depuis l'option ou demande à l'utilisateur
     */
    protected function getTertiaryColor(): ?string
    {
        $tertiary = $this->option('tertiary');
        
        // Si --no-tertiary est défini, on ne demande pas et on retourne null
        if ($this->option('no-tertiary')) {
            return null;
        }
        
        if (!$tertiary) {
            $tertiary = $this->ask('Couleur tertiaire optionnelle (format hex ex: #10B981, ou appuyez sur Entrée pour ignorer)', '');
        }

        if (!$tertiary || $tertiary === '') {
            return null;
        }

        $tertiary = $this->colorService->normalizeHexColor($tertiary);
        
        return $this->colorService->validateHexColor($tertiary) ? $tertiary : null;
    }

    /**
     * Affiche une palette de couleurs dans la console
     */
    protected function displayPalette(string $name, array $palette): void
    {
        $this->line("Palette {$name}:");
        foreach ($palette as $shade => $color) {
            $this->line("  {$shade}: {$color}");
        }
        $this->newLine();
    }
}
