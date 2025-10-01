<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Builder\Block;

use Filament\Forms\Components\DateTimePicker;
use App\Filament\Forms\Components\OptimizingFileUpload;
use App\Models\Page;
use App\Filament\Forms\Components\RichEditor\Plugins\PageLinkPlugin;
use App\Filament\Forms\Components\RichEditor\Plugins\OrderedListPlugin;

class PageForm
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)->schema([
                    Section::make('Contenu')
                        ->schema([
                            Builder::make('contents')
                                ->label('Blocks de contenu')
                                ->blockPreviews()
                                ->collapsible()
                                ->cloneable()
                                ->grow()
                                ->blocks([
                                    Block::make('hero')
                                        ->label('Bannière Hero')
                                        ->schema([
                                            Hidden::make('block_id')
                                                ->default(fn() => (string) \Illuminate\Support\Str::uuid()),
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            RichEditor::make('title')
                                                                ->label('Titre')
                                                                ->required()
                                                                ->toolbarButtons([
                                                                    'bold', 'italic', 'link', 'pageLink'
                                                                ])
                                                                ->plugins([PageLinkPlugin::make()]),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                            TextInput::make('anchor')
                                                                ->label('Ancre (ID)')
                                                                ->helperText('Identifiant unique pour créer un lien vers cette section')
                                                                ->placeholder('ex: ma-section'),
                                                            Repeater::make('boutons')
                                                                ->label('Boutons')
                                                                ->schema([
                                                                    TextInput::make('texte')
                                                                        ->label('Texte du bouton')
                                                                        ->required(),
                                                                    Select::make('couleur')
                                                                        ->label('Couleur')
                                                                        ->options([
                                                                            'primary' => 'Primaire',
                                                                            'secondary' => 'Secondaire',
                                                                        ])
                                                                        ->default('primary')
                                                                        ->required(),
                                                                    Select::make('type_lien')
                                                                        ->label('Type de lien')
                                                                        ->options([
                                                                            'page' => 'Page du site',
                                                                            'ancre' => 'Ancre (même page)',
                                                                            'externe' => 'URL externe',
                                                                        ])
                                                                        ->default('page')
                                                                        ->live()
                                                                        ->required(),
                                                                    Select::make('page_id')
                                                                        ->label('Page')
                                                                        ->options(\App\Models\Page::pluck('titre', 'slug'))
                                                                        ->visible(fn ($get) => $get('type_lien') === 'page'),
                                                                    TextInput::make('ancre')
                                                                        ->label('Ancre')
                                                                        ->placeholder('ex: #ma-section')
                                                                        ->visible(fn ($get) => $get('type_lien') === 'ancre'),
                                                                    TextInput::make('url_externe')
                                                                        ->label('URL externe')
                                                                        ->placeholder('https://exemple.com')
                                                                        ->visible(fn ($get) => $get('type_lien') === 'externe'),
                                                                ])
                                                                ->collapsible()
                                                                ->defaultItems(2)
                                                                ->maxItems(4),
                                                        ]),
                                                    Tab::make('Image de fond')
                                                        ->schema([
                                                            OptimizingFileUpload::make('background_image')
                                                                ->label('Image de fond')
                                                                ->disk('public')
                                                                ->directory('hero-images')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(1920)
                                                                ->maxImageHeight(1080)
                                                                ->jpegQuality(85)             
                                                                ->image()
                                                                ->imageEditor()
                                                                ->autoHelperText(),
                                                            Select::make('couche_blanc')
                                                                ->label('Couche de blanc')
                                                                ->helperText('La couche de blanc permet d\'ajuster la lisibilité du texte sur des images')
                                                                ->options([
                                                                    'aucun' => 'Aucun',
                                                                    'normal' => 'Normal',
                                                                    'fort' => 'Fort',
                                                                ])
                                                                ->default('normal'),
                                                            Select::make('direction_couleur')
                                                                ->label('Direction des couleurs')
                                                                ->options([
                                                                    'primaire-secondaire' => 'Primaire vers secondaire',
                                                                    'secondaire-primaire' => 'Secondaire vers primaire',
                                                                    'aucun' => 'Aucun',
                                                                ])
                                                                ->default('primaire-secondaire'),
                                                        ]),
                                                ]),
                                        ])
                                        ->preview('filament.content.block-previews.hero'),

                                    Block::make('content')
                                        ->label('Contenu classique')
                                        ->schema([
                                            Hidden::make('block_id')
                                                ->default(fn() => (string) \Illuminate\Support\Str::uuid()),
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            RichEditor::make('title')
                                                                ->label('Titre')
                                                                ->toolbarButtons([
                                                                    'bold', 'italic'
                                                                ]),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                            TextInput::make('anchor')
                                                                ->label('Ancre (ID)')
                                                                ->helperText('Identifiant unique pour créer un lien vers cette section')
                                                                ->placeholder('ex: ma-section'),
                                                            RichEditor::make('texts')
                                                                ->label('Texte')
                                                                ->toolbarButtons([
                                                                    'bold', 'italic', 'underline', 'pageLink', 'bulletList', 'orderedList', 'customOrderedList', 'blockquote', 'clearFormatting' 
                                                                ])
                                                                ->plugins([PageLinkPlugin::make(), OrderedListPlugin::make()]),
                                                            Toggle::make('secondary_text')
                                                                ->label('Texte secondaire')
                                                                ->helperText('Remplace l\'image par un second encart de texte')
                                                                ->default(false)
                                                                ->live(),
                                                            RichEditor::make('secondary_content')
                                                                ->label('Contenu du texte secondaire')
                                                                ->visible(fn ($get) => $get('secondary_text'))
                                                                ->toolbarButtons([
                                                                    'bold', 'italic', 'underline', 'link', 'pageLink', 'bulletList', 'orderedList', 'customOrderedList', 'blockquote', 'clearFormatting' 
                                                                ])
                                                                ->plugins([PageLinkPlugin::make(), OrderedListPlugin::make()]),
                                                            Toggle::make('image_right')
                                                                ->label('Image à droite')
                                                                ->helperText('Par défaut, l\'image est à gauche')
                                                                ->default(false)
                                                                ->hidden(fn ($get) => $get('secondary_text')),
                                                        ]),
                                                    Tab::make('Photo')
                                                        ->schema([
                                                            OptimizingFileUpload::make('background_image')
                                                                ->label('Photo')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(800)
                                                                ->maxImageHeight(800)
                                                                ->avatar()
                                                                ->autoHelperText()
                                                                ->directory('testimonials')
                                                                ->maxSize(5000),
                                                        ])
                                                        ->hidden(fn ($get) => $get('secondary_text')),
                                                    Tab::make('Image de fond')
                                                        ->schema([
                                                            OptimizingFileUpload::make('section_background_image')
                                                                ->label('Image de fond de section')
                                                                ->disk('public')
                                                                ->directory('content-bg-images')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(1920)
                                                                ->maxImageHeight(1080)
                                                                ->jpegQuality(85)             
                                                                ->image()
                                                                ->imageEditor()
                                                                ->autoHelperText(),
                                                            Select::make('couche_blanc')
                                                                ->label('Couche de blanc')
                                                                ->helperText('La couche de blanc permet d\'ajuster la lisibilité du texte sur des images')
                                                                ->options([
                                                                    'aucun' => 'Aucun',
                                                                    'normal' => 'Normal',
                                                                    'fort' => 'Fort',
                                                                ])
                                                                ->default('aucun'),
                                                            Select::make('direction_couleur')
                                                                ->label('Direction des couleurs')
                                                                ->options([
                                                                    'primaire-secondaire' => 'Primaire vers secondaire',
                                                                    'secondaire-primaire' => 'Secondaire vers primaire',
                                                                    'aucun' => 'Aucun',
                                                                ])
                                                                ->default('aucun'),
                                                        ]),
                                                    Tab::make('Style')
                                                        ->schema([
                                                            Select::make('couleur_primaire')
                                                                ->label('Couleur du titre')
                                                                ->options([
                                                                    'primary' => 'Primaire',
                                                                    'secondary' => 'Secondaire',
                                                                ])
                                                                ->default('secondary'),
                                                            Select::make('style_listes')
                                                                ->label('Style des listes')
                                                                ->options([
                                                                    'alternance' => 'Alternance primary/secondary',
                                                                    'primary' => 'Primaire uniquement',
                                                                    'secondary' => 'Secondaire uniquement',
                                                                ])
                                                                ->default('alternance'),
                                                            Toggle::make('afficher_separateur')
                                                                ->label('Afficher un séparateur')
                                                                ->helperText('Affiche une ligne de séparation sous le contenu')
                                                                ->default(false),
                                                        ]),
                                                ]),
                                        ])
                                        ->preview('filament.content.block-previews.content'),
                                ]),
                        ])->columnSpan(3),
                    Section::make('Détails de la page')
                        ->schema([
                            TextInput::make('titre')
                                ->required(),
                            TextInput::make('slug')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'draft' => 'Brouillon',
                                    'published' => 'Publié',
                                    'archived' => 'Archivé',
                                ])
                                ->default('draft'),
                            Toggle::make('is_homepage')
                                ->label('Page d\'accueil')
                                ->helperText('Une seule page peut être définie comme page d\'accueil'),
                            Toggle::make('is_in_header')
                                ->label('Afficher dans le header'),
                            Toggle::make('is_in_footer')
                                ->label('Afficher dans le footer'),
                            TextInput::make('key_word')
                                ->label('Mots-clés SEO'),
                            DateTimePicker::make('published_at')
                                ->label('Date de publication'),
                        ])->columnSpan(1),
                    
                ]),
            ]);
    }
}
