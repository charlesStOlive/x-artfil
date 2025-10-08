<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin.email', 'admin@example.com');
        $this->migrator->add('admin.telephone', '+33 1 23 45 67 89');
        $this->migrator->add('admin.adresse', '123 Rue de l\'Exemple, 75001 Paris');
        $this->migrator->add('admin.horaire', 'Lundi - Vendredi: 9h00 - 18h00');
        $this->migrator->add('admin.mailRecepteur', 'contact@example.com');
        $this->migrator->add('admin.logo', null);
    }
};
