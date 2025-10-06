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
    private ?array $blockData = null;

    public function __construct(string $mode = 'front', ?string $blockId = null, mixed $page = null)
    {
        $this->mode = $mode;
        $this->blockId = $blockId;
        $this->page = $page;
    }

    /**
     * Créer une instance depuis un bloc et ses données directement
     *
     * @param array $blockData Données du bloc ($block['data'])
     * @param string $mode Mode d'affichage
     * @param mixed|null $page Instance de page
     * @return static
     */
    public static function fromBlockData(array $blockData, string $mode = 'front', mixed $page = null): static
    {
        $blockId = $blockData['block_id'] ?? null;
        $instance = new static($mode, $blockId, $page);
        $instance->blockData = $blockData; // Stocker les données pour un accès direct
        return $instance;
    }

    /**
     * Obtenir l'URL d'une image (FileUpload simple)
     *
     * @param mixed $image Image du bloc (string path)
     * @return string|null
     */
    public function getImageUrl(mixed $image): ?string
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
     * Obtenir le contenu HTML formaté depuis RichEditor
     *
     * @param mixed $content Contenu du RichEditor (JSON Tiptap ou HTML)
     * @return string|null
     */
    public function getHtmlContent(mixed $content): ?string
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
     * Obtenir une valeur de données de bloc avec fallback
     *
     * @param array $blockData
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getData(array $blockData, string $key, mixed $default = null): mixed
    {
        $value = $blockData[$key] ?? $default;

        // Éviter les arrays vides pour les champs texte
        if (is_array($value) && empty($value)) {
            return $default;
        }

        return $value;
    }

    /**
     * Résoudre l'image en mode preview (fichiers temporaires Filament)
     *
     * @param mixed $image
     * @return string|null
     */
    private function resolvePreviewImage(mixed $image): ?string
    {
        \Log::info('resolvePreviewImage');
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
     * Obtenir une valeur de données depuis section_styles ou fallback vers la racine
     *
     * @param string $key Clé de la donnée (background_image, couche_blanc, direction_couleur, etc.)
     * @param mixed $default Valeur par défaut
     * @return mixed
     */
    public function getSectionStyleFrom(string $key, mixed $default = null): mixed
    {
        $blockData = $this->blockData ?? [];
        
        // D'abord chercher dans section_styles
        if (isset($blockData['section_styles'][$key])) {
            return $blockData['section_styles'][$key];
        }
        
        // Fallback vers la racine pour compatibilité avec les anciens blocs
        return $this->getData($blockData, $key, $default);
    }

    /**
     * Obtenir l'URL d'une image depuis section_styles ou fallback vers la racine
     *
     * @param string $key Clé de l'image (background_image, etc.)
     * @return string|null
     */
    public function getSectionImageFrom(string $key): ?string
    {
        $blockData = $this->blockData ?? [];
        
        // D'abord chercher dans section_styles
        if (isset($blockData['section_styles'][$key])) {
            return $this->getImageUrl($blockData['section_styles'][$key]);
        }
        
        // Fallback vers la racine pour compatibilité avec les anciens blocs
        return $this->getImageUrl($blockData[$key] ?? null);
    }

    /**
     * Obtenir toutes les données de style de section formatées pour le composant section
     *
     * @return array
     */
    public function getSectionStyles(): array
    {
        $blockData = $this->blockData ?? [];
        $sectionStyles = $blockData['section_styles'] ?? [];
        
        // Données avec fallback vers la racine pour compatibilité
        $backgroundImage = $this->getSectionImageFrom('background_image');
        $coucheBlanc = $this->getSectionStyleFrom('couche_blanc', 'aucun');
        $directionCouleur = $this->getSectionStyleFrom('direction_couleur', 'aucun');
        $is_hidden = $this->getSectionStyleFrom('is_hidden', false);
        
        return [
            'background_image' => $backgroundImage,
            'couche_blanc' => $coucheBlanc,
            'direction_couleur' => $directionCouleur,
            'is_hidden' => $is_hidden,
        ];
    }

    /**
     * Obtenir une donnée simple du bloc (texte, titre, etc.)
     *
     * @param string $key Clé de la donnée (title, text, layout, etc.)
     * @param mixed $default Valeur par défaut
     * @return mixed
     */
    public function getDataFrom(string $key, mixed $default = null): mixed
    {
        return $this->getData($this->blockData ?? [], $key, $default);
    }

    /**
     * Obtenir le contenu HTML formaté d'une clé donnée
     *
     * @param string $key Clé du contenu HTML (html_content, description, etc.)
     * @return string|null
     */
    public function getHtmlFrom(string $key): ?string
    {
        return $this->getHtmlContent($this->blockData[$key] ?? null);
    }

    /**
     * Obtenir l'URL d'une image d'une clé donnée
     *
     * @param string $key Clé de l'image (background_image, image, images, etc.)
     * @return string|null
     */
    public function getImageFrom(string $key): ?string
    {
        return $this->getImageUrl($this->blockData[$key] ?? null);
    }

    /**
     * Getters pour accéder aux propriétés
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    public function getBlockId(): ?string
    {
        return $this->blockId;
    }

    public function getPage(): mixed
    {
        return $this->page;
    }

    /**
     * Récupérer toutes les données de configuration photo depuis le statePath
     *
     * @return array Tableau avec 'url', 'display_type', 'position'
     */
    public function getDataForPhotoFrom(?string $key = 'photo_config'): array
    {
        $photoConfig = $this->blockData[$key] ?? [];
        
        return [
            'url' => $this->getImageUrl($photoConfig['url'] ?? null),
            'display_type' => $photoConfig['display_type'] ?? 'mask_brush_square',
            'position' => $photoConfig['position'] ?? 'center',
        ];
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
     * @return array
     */
    public static function extractDataFromBladeVars($vars): array
    {
        $systemVars = ['__env', '__data', 'obLevel', '__path', 'app', 'errors', 'settings', 'user', 'component', 'attributes', 'slot'];
        return array_diff_key($vars, array_flip($systemVars));
    }
}
