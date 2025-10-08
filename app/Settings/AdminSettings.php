<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdminSettings extends Settings
{
    public string $email;
    public string $telephone;
    public string $adresse;
    public string $horaire;
    public string $mailRecepteur;
    public ?string $logo;

    public static function group(): string
    {
        return 'admin';
    }

    public static function encrypted(): array
    {
        return [];
    }
}