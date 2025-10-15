<?php

namespace App\Livewire\Concerns;

trait HandlesConstructionMode
{
    /**
     * Vérifie si le mode construction est actif et redirige si nécessaire
     */
    protected function checkConstruction(): void
    {
        $settings = app(\App\Settings\AdminSettings::class);
        
        if (($settings->construction['activate'] ?? false) && !auth()->check()) {
            redirect()->route('construction');
        }
    }
}