<?php

namespace App\Livewire\Front;

use App\Models\Page;
use Livewire\Component;

class StaticPages extends Component
{

    public $page;
    public $slug;

    public function mount($slug)
    {
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
            ->layout('layouts.front')
            ->title($this->page->titre);
    }
}
