<?php

namespace App\Services;

use OzdemirBurak\Iris\Color\Hex;

class ColorPaletteService
{
    /**
     * Génère une palette complète de couleurs à partir d'une couleur de base
     */
    public function generatePalette(string $baseColor): array
    {
        $hex = new Hex($baseColor);
        
        // Conversion en HSL pour manipuler la luminosité
        $hsl = $hex->toHsl();
        
        return [
            '50'  => $this->adjustLightness($hsl, 95)->toHex()->__toString(),
            '100' => $this->adjustLightness($hsl, 90)->toHex()->__toString(),
            '200' => $this->adjustLightness($hsl, 80)->toHex()->__toString(),
            '300' => $this->adjustLightness($hsl, 70)->toHex()->__toString(),
            '400' => $this->adjustLightness($hsl, 60)->toHex()->__toString(),
            '500' => $baseColor, // Couleur de base
            '600' => $this->adjustLightness($hsl, 40)->toHex()->__toString(),
            '700' => $this->adjustLightness($hsl, 30)->toHex()->__toString(),
            '800' => $this->adjustLightness($hsl, 20)->toHex()->__toString(),
            '900' => $this->adjustLightness($hsl, 10)->toHex()->__toString(),
        ];
    }

    /**
     * Génère une couleur secondaire complémentaire
     */
    public function generateComplementaryColor(string $primaryColor): string
    {
        $hex = new Hex($primaryColor);
        $hsl = $hex->toHsl();
        
        // Rotation de 180° sur le cercle chromatique pour obtenir la couleur complémentaire
        $complementaryHue = ($hsl->hue() + 180) % 360;
        
        // Ajustement de la saturation et luminosité pour harmoniser
        $saturation = max(30, min(70, $hsl->saturation() * 0.8));
        $lightness = max(40, min(70, $hsl->lightness()));
        
        return $hsl->hue($complementaryHue)
                   ->saturation($saturation)
                   ->lightness($lightness)
                   ->toHex()
                   ->__toString();
    }

    /**
     * Génère les variables CSS pour un thème front (avec palette complète)
     */
    public function generateThemeVariables(array $primaryPalette, array $secondaryPalette, ?array $tertiaryPalette = null): string
    {
        $css = '';
        
        // Palette complète primary (50-900)
        foreach ($primaryPalette as $shade => $color) {
            $css .= "  --color-primary-{$shade}: {$color};\n";
        }
        
        // Palette complète secondary (50-900)
        foreach ($secondaryPalette as $shade => $color) {
            $css .= "  --color-secondary-{$shade}: {$color};\n";
        }
        
        // Palette complète tertiary (50-900) si définie
        if ($tertiaryPalette) {
            foreach ($tertiaryPalette as $shade => $color) {
                $css .= "  --color-tertiary-{$shade}: {$color};\n";
            }
        }
        
        return $css;
    }

    /**
     * Génère les variables CSS pour un thème filament (palette complète également)
     */
    public function generateFilamentThemeVariables(array $primaryPalette, array $secondaryPalette, ?array $tertiaryPalette = null): string
    {
        $css = '';
        
        // Palette complète primary (50-900) pour Filament aussi
        foreach ($primaryPalette as $shade => $color) {
            $css .= "  --color-primary-{$shade}: {$color};\n";
        }
        
        // Palette complète secondary (50-900) pour Filament aussi
        foreach ($secondaryPalette as $shade => $color) {
            $css .= "  --color-secondary-{$shade}: {$color};\n";
        }
        
        // Palette complète tertiary (50-900) pour Filament aussi si définie
        if ($tertiaryPalette) {
            foreach ($tertiaryPalette as $shade => $color) {
                $css .= "  --color-tertiary-{$shade}: {$color};\n";
            }
        }
        
        return $css;
    }

    /**
     * Met à jour un fichier CSS avec les nouvelles variables de thème
     */
    public function updateCssFile(string $filePath, array $primaryPalette, array $secondaryPalette, ?array $tertiaryPalette = null): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $content = file_get_contents($filePath);
        
        // Détermine le type de variables à générer selon le fichier
        $isFilamentFile = strpos($filePath, 'filament') !== false;
        $themeVariables = $isFilamentFile 
            ? $this->generateFilamentThemeVariables($primaryPalette, $secondaryPalette, $tertiaryPalette)
            : $this->generateThemeVariables($primaryPalette, $secondaryPalette, $tertiaryPalette);
        
        // Pattern pour détecter le bloc à remplacer
        $pattern = '/(\/\* color-schema-console-generated-start \*\/\s*\n)(.*?)(\/\* color-schema-console-generated-end \*\/)/s';
        
        $replacement = '$1' . $themeVariables . '  $3';
        
        $updatedContent = preg_replace($pattern, $replacement, $content);
        
        return file_put_contents($filePath, $updatedContent) !== false;
    }

    /**
     * Met à jour le provider Filament avec les nouvelles couleurs
     */
    public function updateFilamentProvider(string $primaryColor, string $secondaryColor): bool
    {
        $providerPath = app_path('Providers/Filament/AdminPanelProvider.php');
        
        if (!file_exists($providerPath)) {
            return false;
        }

        $content = file_get_contents($providerPath);
        
        // Pattern pour détecter et remplacer les couleurs dans le provider
        $pattern = '/->colors\(\s*\[\s*\'primary\'\s*=>\s*\'[^\']*\',\s*\'secondary\'\s*=>\s*\'[^\']*\',\s*\]\s*\)/';
        
        $replacement = "->colors([\n                'primary' => '{$primaryColor}',\n                'secondary' => '{$secondaryColor}',\n            ])";
        
        $updatedContent = preg_replace($pattern, $replacement, $content);
        
        return file_put_contents($providerPath, $updatedContent) !== false;
    }

    /**
     * Ajuste la luminosité d'une couleur HSL
     */
    private function adjustLightness($hsl, float $lightness): \OzdemirBurak\Iris\Color\Hsl
    {
        return $hsl->lightness($lightness);
    }

    /**
     * Valide qu'une couleur hex est correcte
     */
    public function validateHexColor(string $color): bool
    {
        return preg_match('/^#[0-9A-F]{6}$/i', $color) === 1;
    }

    /**
     * Normalise une couleur hex (ajoute # si nécessaire)
     */
    public function normalizeHexColor(string $color): string
    {
        $color = ltrim($color, '#');
        return '#' . strtoupper($color);
    }
}