<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        // Ajouter le texte de footer
        $this->migrator->add('admin.footerText', 'Copyright © 2025. Tous droits réservés.');
        
        // Ajouter les paramètres de construction/maintenance
        $this->migrator->add('admin.construction', [
            'activate' => false,
            'titre' => 'Site en maintenance',
            'description' => 'Nous travaillons actuellement sur notre site. Nous serons de retour bientôt !'
        ]);
    }
};
