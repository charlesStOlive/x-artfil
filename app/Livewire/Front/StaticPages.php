<?php

namespace App\Livewire\Front;

use App\Models\Page;
use Livewire\Component;
use App\Livewire\Concerns\HandlesConstructionMode;

class StaticPages extends Component
{
    use HandlesConstructionMode;

    public $page;
    public $slug;

    public function mount($slug)
    {
        $this->checkConstruction();
        
        $this->slug = $slug;
        // Récupérer la page par slug ou afficher 404
        $this->page = Page::where('slug', $slug)->first();
        if (!$this->page) {
            abort(404, "Page '{$slug}' non trouvée");
        }
    }

    
    public function render()
    {
        return view('livewire.front.static-page')
            ->layout('layouts.front', [
                'hasForm' => $this->page->has_form,
                'metaDescription' => $this->page->meta_description,
                'metaKeywords' => $this->page->meta_keywords,
            ])
            ->title($this->page->titre);
    }
}
