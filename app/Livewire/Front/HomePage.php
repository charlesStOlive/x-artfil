<?php

namespace App\Livewire\Front;

use App\Models\Page;
use Livewire\Component;

class HomePage extends Component
{

    public $page;
    public $slug;

    public function mount($slug = 'home')
    {
        $this->slug = $slug;
        
        // Récupérer la page par slug ou afficher 404
        $this->page = Page::where('is_homepage', true)->first();
        \Log::info($this->page);
        if (!$this->page) {
            abort(404, "Page '{$slug}' non trouvée");
        }
    }

    
    public function render()
    {
        return view('livewire.front.home-page')
            ->layout('layouts.front')
            ->title('Accueil - X-Artfil');
    }
}
