<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdminSettings extends Settings
{
    // Informations de contact
    public string $email;
    public string $telephone;
    public string $adresse;
    public string $horaire;
    public string $mailRecepteur;
    
    // Options visuelles
    public ?string $logo;
    public string $footerText;
    
    // Construction/Maintenance
    public array $construction;

    public static function group(): string
    {
        return 'admin';
    }

    public static function encrypted(): array
    {
        return [];
    }
}