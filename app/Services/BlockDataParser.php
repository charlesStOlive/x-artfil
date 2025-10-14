<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BlockDataParser
{
    private string $mode;
    private ?string $blockId;
    private mixed $page;
    private array $processedData = [];

    public function __construct(string $mode = 'front', ?string $blockId = null, mixed $page = null)
    {
        $this->mode = $mode;
        $this->blockId = $blockId;
        $this->page = $page;
    }

    /**
     * Créer une instance depuis un bloc et ses données directement
     * Traite automatiquement toutes les données selon leurs préfixes
     *
     * @param array $blockData Données du bloc ($block['data'])
     * @param string $mode Mode d'affichage
     * @param mixed|null $page Instance de page
     * @return array Données traitées directement utilisables
     */
    public static function fromBlockData(array $blockData, string $mode = 'front', mixed $page = null): array
    {
        $instance = new static($mode, null, $page);
        return $instance->processAllData($blockData);
    }

    /**
     * Traite tous les blocs d'une page et retourne les données prêtes pour l'injection
     * Utile pour traiter en amont dans le contrôleur/Livewire
     *
     * @param array $blocks Array de blocs avec structure ['type' => '...', 'data' => [...]]
     * @param string $mode Mode d'affichage
     * @param mixed|null $page Instance de page
     * @return array Blocs avec données traitées
     */
    public static function processPageBlocks(array $blocks, string $mode = 'front', mixed $page = null): array
    {
        $processedBlocks = [];
        
        foreach ($blocks as $block) {
            $processedData = static::fromBlockData($block['data'] ?? [], $mode, $page);
            
            $processedBlocks[] = [
                'type' => $block['type'] ?? '',
                'data' => $processedData,
                'processed' => true, // Marquer comme déjà traité
            ];
        }
        
        return $processedBlocks;
    }

    /**
     * Traite automatiquement toutes les données selon leur type/préfixe
     *
     * @param array $blockData
     * @return array
     */
    private function processAllData(array $blockData): array
    {
        $processed = [];
        
        foreach ($blockData as $key => $value) {
            $processed[$key] = $this->processDataValue($key, $value);
        }
        
        return $processed;
    }

    /**
     * Traite une valeur selon son type/préfixe
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    private function processDataValue(string $key, mixed $value)
    {
        // Si la valeur est null ou vide, retourner telle quelle
        if ($value === null || (is_array($value) && empty($value))) {
            return $value;
        }

        // Traitement des images (préfixe image_ ou images_)
        if (str_starts_with($key, 'image_') || str_starts_with($key, 'images_')) {
            if (str_starts_with($key, 'images_')) {
                // Images multiples
                return $this->processMultipleImages($value);
            } else {
                // Image simple
                return $this->processImageUrl($value);
            }
        }

        // Traitement du contenu HTML (préfixe html_)
        if (str_starts_with($key, 'html_')) {
            return $this->processHtmlContent($value);
        }

        // Traitement des objets imbriqués (comme photo_config)
        if (is_array($value) && $this->isAssociativeArray($value)) {
            $processed = [];
            foreach ($value as $subKey => $subValue) {
                $processed[$subKey] = $this->processDataValue($subKey, $subValue);
            }
            return $processed;
        }

        // Valeur normale, retourner telle quelle
        return $value;
    }

    /**
     * Traite une URL d'image
     *
     * @param mixed $image
     * @return string|null
     */
    private function processImageUrl(mixed $image): ?string
    {
        if (!$image) {
            return null;
        }

        // En mode preview, utiliser les fichiers temporaires Filament
        if ($this->mode === 'preview') {
            return $this->resolvePreviewImage($image);
        }

        // En mode front, retourner l'URL simple
        if (is_string($image)) {
            return \Storage::disk('public')->url($image);
        }

        return null;
    }

    /**
     * Traite plusieurs images
     *
     * @param mixed $images
     * @return array|null
     */
    private function processMultipleImages(mixed $images): ?array
    {
        if (!is_array($images)) {
            return null;
        }

        $processed = [];
        foreach ($images as $image) {
            $url = $this->processImageUrl($image);
            if ($url) {
                $processed[] = $url;
            }
        }

        return empty($processed) ? null : $processed;
    }

    /**
     * Traite le contenu HTML
     *
     * @param mixed $content
     * @return string|null
     */
    private function processHtmlContent(mixed $content): ?string
    {
        if (!$content) {
            return null;
        }

        if (is_string($content)) {
            return $this->cleanInternalLinks($content);
        }

        if (is_array($content)) {
            // Utiliser le RichContentRenderer avec les plugins pour convertir le JSON TipTap
            $renderer = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($content)
                ->plugins([
                    \App\Filament\Forms\Components\RichEditor\Plugins\PageLinkPlugin::make(),
                ]);
                
            $html = $renderer->toHtml();
            return $this->cleanInternalLinks($html);
        }

        return null;
    }

    /**
     * Vérifie si un array est associatif
     *
     * @param array $array
     * @return bool
     */
    private function isAssociativeArray(array $array): bool
    {
        if (empty($array)) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Résoudre l'image en mode preview (fichiers temporaires Filament)
     *
     * @param mixed $image
     * @return string|null
     */
    private function resolvePreviewImage(mixed $image): ?string
    {

        if (is_string($image)) {
            if (str_contains($image, '/storage/livewire-tmp/') || str_contains($image, 'http')) {
                return $image;
            }
            $url = asset('storage/' . $image);
            return $url;
        }

        if (is_object($image) && method_exists($image, 'temporaryUrl')) {
            try {
                $tempUrl = $image->temporaryUrl();
                return $tempUrl;
            } catch (\Exception $e) {
                \Log::warning('[BlockDataParser - resolvePreviewImage] Erreur temporaryUrl - fichier probablement déplacé par Spatie', [
                    'error' => $e->getMessage(),
                    'file_class' => get_class($image)
                ]);
                return null;
            }
        }

        if (is_array($image) && !empty($image)) {
            $firstFile = reset($image);
            // Si c'est un objet avec temporaryUrl (TemporaryUploadedFile)
            if (is_object($firstFile) && method_exists($firstFile, 'temporaryUrl')) {
                $tempUrl = $firstFile->temporaryUrl();
                return $tempUrl;
            }

            // Si c'est une chaîne de caractères
            if (is_string($firstFile)) {
                $url = str_contains($firstFile, '/storage/livewire-tmp/')
                    ? $firstFile
                    : asset('storage/' . $firstFile);
                return $url;
            }
        }
        return null;
    }

    /**
     * Nettoyer les liens internes qui ne devraient pas avoir target="_blank"
     * Solution temporaire en attendant la correction du bug TipTap PHP
     * @see https://github.com/filamentphp/filament/issues/16829
     *
     * @param string $content
     * @return string
     */
    private function cleanInternalLinks(string $content): string
    {
        // Nettoyer les liens internes (qui commencent par / ou #) 
        // qui ont target="_blank" alors qu'ils ne devraient pas
        return preg_replace_callback(
            '/<a([^>]*?)href=["\'](\/[^"\']*|#[^"\']*)["\']([^>]*?)>/i',
            function ($matches) {
                $beforeHref = $matches[1];
                $href = $matches[2];
                $afterHref = $matches[3];
                
                // Supprimer target="_blank" et rel="noopener noreferrer nofollow" des liens internes
                $beforeHref = preg_replace('/\s*target=["\']_blank["\']/i', '', $beforeHref);
                $afterHref = preg_replace('/\s*target=["\']_blank["\']/i', '', $afterHref);
                $beforeHref = preg_replace('/\s*rel=["\']noopener noreferrer nofollow["\']/i', '', $beforeHref);
                $afterHref = preg_replace('/\s*rel=["\']noopener noreferrer nofollow["\']/i', '', $afterHref);
                
                return '<a' . $beforeHref . 'href="' . $href . '"' . $afterHref . '>';
            },
            $content
        );
    }

    /**
     * Extraire les données depuis les variables définies dans Blade
     * Utilisé quand les données de bloc sont vides pour le mode preview
     *
     * @param array $vars Variables Blade
     * @return array Données extraites et traitées
     */
    public static function extractDataFromBladeVars(array $vars): array
    {
        $systemVars = ['__env', '__data', 'obLevel', '__path', 'app', 'errors', 'settings', 'user', 'component', 'attributes', 'slot'];
        $extractedData = array_diff_key($vars, array_flip($systemVars));
        
        // Traiter les données extraites avec le parser
        $instance = new static('preview');
        return $instance->processAllData($extractedData);
    }
}
