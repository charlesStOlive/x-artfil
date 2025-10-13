# Exemple d'utilisation optimisée du BlockDataParser V2

## 🚀 Approche 1 : Traitement dans le contrôleur (RECOMMANDÉ)

### Dans votre contrôleur Livewire :

```php
<?php

namespace App\Livewire\Front;

use App\Models\Page;
use App\Services\BlockDataParser;
use Livewire\Component;

class StaticPages extends Component
{
    public $page;
    public $processedBlocks = [];

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->firstOrFail();
        
        // Traiter TOUS les blocs en une seule fois
        $this->processedBlocks = BlockDataParser::processPageBlocks(
            $this->page->contents ?? [], 
            'front', 
            $this->page
        );
    }

    public function render()
    {
        return view('livewire.front.static-page', [
            'blocks' => $this->processedBlocks
        ])->layout('layouts.front');
    }
}
```

### Dans votre template Blade :

```blade
{{-- livewire/front/static-page.blade.php --}}
<div class="page-content">
    @foreach($blocks as $block)
        @if($block['type'] === 'content')
            <x-blocks.content 
                :html_title="$block['data']['html_title'] ?? null"
                :description="$block['data']['description'] ?? null"
                :anchor="$block['data']['anchor'] ?? ''"
                :html_texts="$block['data']['html_texts'] ?? null"
                :image_right="$block['data']['image_right'] ?? false"
                :couleur_primaire="$block['data']['couleur_primaire'] ?? 'secondary'"
                :style_listes="$block['data']['style_listes'] ?? 'alternance'"
                :afficher_separateur="$block['data']['afficher_separateur'] ?? true"
                :photo_config="$block['data']['photo_config'] ?? []"
                :image_background="$block['data']['image_background'] ?? null"
                :couche_blanc="$block['data']['couche_blanc'] ?? 'aucun'"
                :direction_couleur="$block['data']['direction_couleur'] ?? 'aucun'"
                :is_hidden="$block['data']['is_hidden'] ?? false"
            />
        @endif
        
        @if($block['type'] === 'hero')
            <x-blocks.hero 
                :html_title="$block['data']['html_title'] ?? null"
                :description="$block['data']['description'] ?? null"
                :boutons="$block['data']['boutons'] ?? []"
                :couleur_primaire="$block['data']['couleur_primaire'] ?? 'secondary'"
                :image_background="$block['data']['image_background'] ?? null"
                {{-- ... autres props --}}
            />
        @endif
    @endforeach
</div>
```

## 🛠️ Approche 2 : Macro Blade (ENCORE PLUS SIMPLE)

### Créer une macro Blade pour automatiser :

```php
// Dans AppServiceProvider.php
use Illuminate\Support\Facades\Blade;

public function boot()
{
    Blade::directive('blockComponent', function ($expression) {
        return "<?php 
            [$block, $componentName] = {$expression};
            if (isset(\$block['processed']) && \$block['processed']) {
                // Données déjà traitées, injection directe
                echo \$__env->make('components.blocks.' . \$componentName, \$block['data'])->render();
            } else {
                // Fallback ancien système
                echo \$__env->make('components.blocks.' . \$componentName, ['block' => \$block])->render();
            }
        ?>";
    });
}
```

### Usage dans le template :

```blade
{{-- Simplifié à l'extrême --}}
@foreach($blocks as $block)
    @blockComponent([$block, $block['type']])
@endforeach
```

## 🎯 Approche 3 : Composant automatique (LE PLUS ÉLÉGANT)

### Créer un composant Blade intelligent :

```php
// app/View/Components/BlockRenderer.php
<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BlockRenderer extends Component
{
    public function __construct(
        public array $block
    ) {}

    public function render()
    {
        $componentName = $this->block['type'] ?? 'content';
        
        if (isset($this->block['processed']) && $this->block['processed']) {
            // Injection directe des données traitées
            return view("components.blocks.{$componentName}")
                ->with($this->block['data']);
        }
        
        // Fallback
        return view("components.blocks.{$componentName}")
            ->with(['block' => $this->block]);
    }
}
```

### Usage ultra-simplifié :

```blade
{{-- Le plus simple possible --}}
@foreach($blocks as $block)
    <x-block-renderer :block="$block" />
@endforeach
```

## ✨ Résultat

- **Performance** : Traitement en une seule fois au niveau contrôleur
- **Simplicité** : Plus de parsing dans les templates
- **Maintenabilité** : Code plus clair et centralisé
- **Flexibilité** : Compatible avec l'ancien système

Quelle approche préférez-vous ?