<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Schemas\Schema;
use App\Settings\AdminSettings;
use Filament\Pages\SettingsPage;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;

class AdminSettingsPage extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = AdminSettings::class;

    protected static ?string $navigationLabel = 'Paramètres Admin';

    protected static ?string $title = 'Paramètres Administrateur';

    protected static ?int $navigationSort = 99;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de contact')
                    ->description('Configurez les informations de contact de votre organisation')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->placeholder('admin@example.com'),

                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->placeholder('+33 1 23 45 67 89'),

                        Textarea::make('adresse')
                            ->label('Adresse')
                            ->required()
                            ->rows(3)
                            ->placeholder('123 Rue de l\'Exemple, 75001 Paris'),

                        Textarea::make('horaire')
                            ->label('Horaires')
                            ->required()
                            ->rows(3)
                            ->placeholder('Lundi - Vendredi: 9h00 - 18h00'),

                        TextInput::make('mailRecepteur')
                            ->label('Email récepteur')
                            ->email()
                            ->required()
                            ->helperText('Adresse email qui recevra les messages du formulaire de contact')
                            ->placeholder('contact@example.com'),
                    ])
                    ->columns(2),

                Section::make('Branding')
                    ->description('Personnalisez l\'apparence de votre site')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('logos')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'])
                            ->maxSize(2048)
                            ->helperText('Formats acceptés: PNG, JPG, JPEG, SVG. Taille maximum: 2MB'),
                    ]),
            ]);
    }
}
