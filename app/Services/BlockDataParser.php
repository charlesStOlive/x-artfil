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
            return $this->processLinks($content);
        }

        if (is_array($content)) {
            // Utiliser le RichContentRenderer avec les plugins pour convertir le JSON TipTap
            $renderer = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($content)
                ->plugins([
                    \App\Filament\Forms\Components\RichEditor\Plugins\PageLinkPlugin::make(),
                ]);
                
            $html = $renderer->toHtml();
            return $this->processLinks($html);
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
     * Créer une instance depuis les props d'un composant Blade
     *
     * @param array $props Props du composant (block, mode, page)
     * @return static
     */
    public static function fromBladeProps(array $props): static
    {
        $mode = $props['mode'] ?? 'front';
        $blockId = $props['block']['data']['block_id'] ?? null;
        $page = $props['page'] ?? null;

        return new static($mode, $blockId, $page);
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
     * Créer un format de bloc unifié à partir des variables de preview Filament
     * Cette méthode évite d'avoir à reconstruire manuellement le tableau $block
     * 
     * @param string $blockType Type de bloc (hero, testimonial, text-photo, etc.)
     * @param array $previewVariables Variables passées par Filament à la preview (get_defined_vars())
     * @param string $mode Mode d'affichage ('preview', 'front', etc.)
     * @return array Format de bloc unifié
     */
    public static function createBlockFromPreview(string $blockType, array $previewVariables, string $mode = 'preview'): array
    {
        // Exclure les variables système de Blade
        $systemVariables = [
            '__env', '__data', 'obLevel', '__path', 'app', 'errors', 'settings', 'user', 
            'component', 'attributes', 'slot', '__componentOriginal', '__currentLoopData', 
            'loop', '__isset_validation_factory'
        ];

        // Filtrer les variables système et garder seulement les données du bloc
        $blockData = [];
        foreach ($previewVariables as $key => $value) {
            if (!in_array($key, $systemVariables) && !str_starts_with($key, '__')) {
                $blockData[$key] = $value;
            }
        }

        return [
            'type' => $blockType,
            'data' => $blockData
        ];
    }

    /**
     * Traite le contenu HTML pour convertir les merge tags et liens relatifs
     *
     * @param string|null $content Contenu HTML à traiter
     * @return string|null
     */
    public function processLinks(?string $content): ?string
    {
        if (!$content) {
            return $content;
        }

        // Traiter les merge tags d'abord
        $content = $this->processMergeTags($content);

        // En mode preview, pas de traitement des liens relatifs
        if ($this->mode === 'preview') {
            return $content;
        }

        // Remplacer les liens relatifs par des URLs absolues
        $content = preg_replace_callback(
            '/<a([^>]*?)href=["\'](\/[^"\']*)["\']([^>]*?)>/i',
            function ($matches) {
                $beforeHref = $matches[1];
                $href = $matches[2];
                $afterHref = $matches[3];

                // Construire l'URL absolue
                $absoluteUrl = rtrim(config('app.url'), '/') . $href;

                return '<a' . $beforeHref . 'href="' . $absoluteUrl . '"' . $afterHref . '>';
            },
            $content
        );

        return $content;
    }

    /**
     * Remplace les merge tags par les vrais liens
     *
     * @param string $content
     * @return string
     */
    private function processMergeTags(string $content): string
    {
        // Remplacer les merge tags de pages par de vrais liens
        $content = preg_replace_callback(
            '/\{page_([^}]+)\}/',
            function ($matches) {
                $slug = $matches[1];
                $page = \App\Models\Page::where('slug', $slug)->where('status', 'published')->first();
                
                if ($page) {
                    return '<a href="/' . $slug . '">' . htmlspecialchars($page->titre) . '</a>';
                }
                
                return $matches[0]; // Retourner le tag original si la page n'existe pas
            },
            $content
        );

        // Remplacer d'autres merge tags
        $replacements = [
            '{site_url}' => '<a href="' . config('app.url') . '">' . config('app.name') . '</a>',
        ];

        foreach ($replacements as $tag => $replacement) {
            $content = str_replace($tag, $replacement, $content);
        }

        return $content;
    }

    /**
     * Méthode de convenance pour créer directement un parser depuis les variables de preview
     * 
     * @param string $blockType Type de bloc
     * @param array $previewVariables Variables de preview 
     * @param string $mode Mode d'affichage
     * @param mixed|null $page Instance de page
     * @return static
     */
    public static function fromPreviewVariables(string $blockType, array $previewVariables, string $mode = 'preview', mixed $page = null): static
    {
        $block = self::createBlockFromPreview($blockType, $previewVariables, $mode);
        return self::fromBlockData($block['data'], $mode, $page);
    }
}
