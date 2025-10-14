<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class OptimizingFileUpload extends FileUpload
{
    /** 'webp' | 'jpeg' | 'jpg' | 'png' | null */
    protected string | \Closure | null $optimizeFormat = null;

    /** pour 'jpeg'/'jpg' seulement */
    protected int | \Closure $jpegQuality = 70;

    protected int | \Closure | null $maxW = null;
    protected int | \Closure | null $maxH = null;

    /** réduction proportionnelle en %, 0..100 (optionnel) */
    protected int | \Closure | null $resizePct = null;

    /** on mémorise si l'utilisateur a demandé preserveFilenames() */
    protected bool | \Closure $preserveFilenamesRequested = false;

    /** flag pour savoir si le helperText a été défini manuellement */
    protected bool $hasCustomHelperText = false;

    protected function setUp(): void
    {
        parent::setUp();



        // Force l'override du callback d'optimisation
        $this->saveUploadedFileUsing(static function (OptimizingFileUpload $component, TemporaryUploadedFile $file): ?string {
            
            // 1) Si on nous demande une conversion webp + preserveFilenames => erreur explicite
            if (
                    $component->evaluate($component->optimizeFormat) === 'webp'
                    && $component->evaluate($component->preserveFilenamesRequested)
                ) {
                    throw new \LogicException(
                        "Incompatible: optimize('webp') n'est pas compatible avec preserveFilenames(). ".
                        "Désactive preserveFilenames() ou retire optimize('webp')."
                    );
                }

                // 2) Non-image => fallback standard Filament
                if (! str_contains($file->getMimeType(), 'image')) {
                    $method = $component->getVisibility() === 'public' ? 'storePubliclyAs' : 'storeAs';
                    
                    return $file->{$method}(
                        trim($component->getDirectory() ?? '', '/'),
                        $file->getClientOriginalName(),
                        $component->getDiskName()
                    );
                } else {
                    \Log::error('OptimizingFileUpload: processing image upload');
                }

                // 3) Image => ouvrir avec Intervention + orienter
                $manager = new ImageManager(new Driver());
                $img = $manager->read($file->getRealPath())->orient();

                // a) Redimension par bornes
                $maxW = $component->evaluate($component->maxW);
                $maxH = $component->evaluate($component->maxH);
                
                if ($maxW || $maxH) {
                    $currentWidth = $img->width();
                    $currentHeight = $img->height();
                    
                    // CAS 1: Une seule dimension spécifiée → conserver le ratio
                    if (($maxW && !$maxH) || (!$maxW && $maxH)) {
                        if ($maxW && !$maxH) {
                            // Seulement maxWidth → calculer hauteur proportionnelle
                            if ($currentWidth > $maxW) {
                                $ratio = $currentWidth / $currentHeight;
                                $newWidth = $maxW;
                                $newHeight = (int) round($maxW / $ratio);
                                $img->resize($newWidth, $newHeight);
                            }
                        } else {
                            // Seulement maxHeight → calculer largeur proportionnelle
                            if ($currentHeight > $maxH) {
                                $ratio = $currentWidth / $currentHeight;
                                $newHeight = $maxH;
                                $newWidth = (int) round($maxH * $ratio);
                                $img->resize($newWidth, $newHeight);
                            }
                        }
                    }
                    // CAS 2: Les deux dimensions spécifiées → crop pour rentrer exactement
                    elseif ($maxW && $maxH) {
                        // Redimensionner d'abord pour que l'image couvre entièrement la zone cible
                        $img->cover($maxW, $maxH);
                    }
                }

                // b) Réduction en %
                $resizePct = $component->evaluate($component->resizePct);
                if ($resizePct !== null) {
                    $pct = max(0, min(100, $resizePct));
                    if ($pct > 0) {
                        $newW = (int) round($img->width() * (1 - $pct / 100));
                        $newH = (int) round($img->height() * (1 - $pct / 100));
                        $img->resize($newW, $newH, function ($c) {
                            $c->aspectRatio();
                            $c->upsize();
                        });
                    }
                }

                // 4) Encodage
                $target = $component->evaluate($component->optimizeFormat); // 'webp' | 'jpeg' | 'jpg' | 'png' | null
                $jpegQuality = $component->evaluate($component->jpegQuality);
                try {
                    $encoded = match ($target) {
                        'jpeg', 'jpg' => $img->encode(new JpegEncoder(quality: $jpegQuality)),
                        'webp'        => $img->encode(new WebpEncoder()),
                        'png'         => $img->encode(new PngEncoder()),
                        default       => $img->encode(), // conserve le format d'origine
                    };
                } catch (Throwable $e) {
                    // Si le format n'est pas supporté par GD/Imagick (ex: webp non compilé)
                    throw new \RuntimeException(
                        "Échec de l'encodage image (format: ".($target ?: 'source')."). ".
                        "Vérifie que ton PHP/GD/Imagick supporte ce format.",
                        previous: $e
                    );
                }

                // 5) Calcul du nom final
                //    - Si webp => on RENOMME l'extension en .webp
                //    - On ajoute un ULID pour éviter les collisions
                $finalExt = $target ?: (pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION) ?: 'bin');

                $filename = $component->buildOptimizedFilename(
                    originalName: $file->getClientOriginalName(),
                    newExt: $finalExt,
                    suffixUlid: Str::ulid()
                );

                $path = ltrim(rtrim($component->getDirectory() ?? '', '/').'/'.$filename, '/');

                // 6) Écrire sur le disque avec un Content-Type cohérent
                $contentType = match (strtolower($finalExt)) {
                    'webp'        => 'image/webp',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'png'         => 'image/png',
                    default       => $file->getMimeType(),
                };

                Storage::disk($component->getDiskName())->put($path, $encoded->toString(), [
                    'visibility'   => $component->getVisibility(),
                    'ContentType'  => $contentType,
                    // Long cache conseillé pour des assets versionnés
                    'CacheControl' => 'public, max-age=31536000, immutable',
                ]);

                // 7) Retourne le chemin stocké (Filament persistera la valeur)
                return $path;
            });


    }

    /**
     * Demande d'optimisation ('webp', 'jpeg', 'png', ou null pour conserver).
     */
    public function optimize(string | \Closure | null $format): static
    {
        $this->optimizeFormat = $format;
        return $this;
    }

    /**
     * Qualité JPEG (si optimize('jpeg'|'jpg')).
     */
    public function jpegQuality(int | \Closure $quality): static
    {
        $this->jpegQuality = $quality;
        return $this;
    }

    public function maxImageWidth(int | \Closure | null $w): static
    {
        $this->maxW = $w;
        return $this;
    }

    public function maxImageHeight(int | \Closure | null $h): static
    {
        $this->maxH = $h;
        return $this;
    }

    /**
     * Réduction proportionnelle en pourcentage (0..100).
     */
    public function resize(int | \Closure | null $percent): static
    {
        $this->resizePct = $percent;
        return $this;
    }

    /**
     * Override du helperText pour marquer qu'il a été défini manuellement
     */
    public function helperText(mixed $text): static
    {
        $this->hasCustomHelperText = true;
        return parent::helperText($text);
    }

    /**
     * On garde la signature de FileUpload, mais on mémorise que l'utilisateur l'a appelée.
     * Si 'webp' est demandé, on lèvera une erreur au moment de l'upload.
     */
    public function preserveFilenames(bool | \Closure $condition = true): static
    {
        $this->preserveFilenamesRequested = $condition;
        return parent::preserveFilenames($condition);
    }

    /**
     * Reconstruit un nom "propre" avec nouvelle extension.
     */
    protected function buildOptimizedFilename(string $originalName, ?string $newExt, ?string $suffixUlid = null): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = Str::slug(Str::ascii($base ?: 'file')) ?: 'file';

        $ext = $newExt ? ltrim(strtolower($newExt), '.') : 'bin';

        if ($suffixUlid) {
            return "{$base}-{$suffixUlid}.{$ext}";
        }

        return "{$base}.{$ext}";
    }

    /**
     * Génère le helper text automatique si aucun n'a été défini manuellement
     */
    protected function generateAutoHelperTextIfNeeded(): void
    {
        if (! $this->hasCustomHelperText) {
            parent::helperText($this->generateAutoHelperText());
        }
    }

    /**
     * Génère automatiquement le helper text selon les optimisations configurées
     */
    protected function generateAutoHelperText(): string
    {
        $optimizations = [];
        
        $format = $this->evaluate($this->optimizeFormat);
        $maxW = $this->evaluate($this->maxW);
        $maxH = $this->evaluate($this->maxH);
        $resizePct = $this->evaluate($this->resizePct);
        $jpegQuality = $this->evaluate($this->jpegQuality);

        // Format de conversion
        if ($format) {
            $optimizations[] = "Format : " . strtoupper((string) $format);
        }

        // Qualité JPEG
        if ($format === 'jpeg' || $format === 'jpg') {
            $optimizations[] = "Qualité : {$jpegQuality}%";
        }

        // Dimensions max
        if ($maxW && $maxH) {
            $optimizations[] = "Taille max : {$maxW}x{$maxH}px";
        } elseif ($maxW) {
            $optimizations[] = "Largeur max : {$maxW}px";
        } elseif ($maxH) {
            $optimizations[] = "Hauteur max : {$maxH}px";
        }

        // Réduction proportionnelle
        if ($resizePct) {
            $optimizations[] = "Réduction : -{$resizePct}%";
        }

        if (empty($optimizations)) {
            return "Image téléchargée sans optimisation";
        }

        return "🔧 Optimisation automatique : " . implode(" • ", $optimizations);
    }

    /**
     * Méthode helper pour optimiser rapidement en WebP
     */
    public static function webp(string $name): static
    {
        return static::make($name)->optimize('webp')->autoHelperText();
    }





    /**
     * Force la génération du helper text automatique
     */
    public function autoHelperText(): static
    {
        $this->generateAutoHelperTextIfNeeded();
        return $this;
    }
}