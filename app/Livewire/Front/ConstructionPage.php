<?php

namespace App\Livewire\Front;

use Livewire\Component;
use App\Settings\AdminSettings;

class ConstructionPage extends Component
{
    public function mount()
    {
        // Si le mode construction n'est pas activé, rediriger vers l'accueil
        $settings = app(AdminSettings::class);
        if (!($settings->construction['activate'] ?? false)) {
            return redirect()->route('home');
        }
    }

    public function render()
    {
        $settings = app(AdminSettings::class);
        
        return view('livewire.front.construction-page')
            ->layout('layouts.construction')
            ->title($settings->construction['titre'] ?? 'Site en maintenance');
    }
}
