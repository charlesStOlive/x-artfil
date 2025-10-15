<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Settings\AdminSettings;
use Filament\Notifications\Notification;

class ConstructionModeWidget extends Widget
{
    protected string $view = 'filament.widgets.construction-mode-widget';
    
    protected static ?int $sort = 1;
    
    protected static ?string $pollingInterval = null;
    
    public bool $isActive = false;

    public function mount(): void
    {
        $settings = app(AdminSettings::class);
        $this->isActive = $settings->construction['activate'] ?? false;
    }

    public function toggleConstruction()
    {
        $settings = app(AdminSettings::class);
        $construction = $settings->construction;
        $construction['activate'] = !$construction['activate'];
        $settings->construction = $construction;
        $settings->save();

        // Mettre à jour l'état local
        $this->isActive = $construction['activate'];

        $status = $construction['activate'] ? 'activé' : 'désactivé';
        
        Notification::make()
            ->title('Mode construction ' . $status)
            ->success()
            ->send();
    }


}
