<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;

use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\FusedGroup;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Forms\Components\OptimizingFileUpload;
use App\Filament\Forms\Components\RichEditor\Plugins\PageLinkPlugin;
use App\Filament\Forms\Components\RichEditor\Plugins\OrderedListPlugin;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Str;

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
                                ->blockNumbers(false)
                                ->editAction(
                                    fn($action) => $action->modalWidth(Width::SevenExtraLarge)
                                )
                                ->extraItemActions([
                                    Action::make('toggleVisibility')
                                        ->icon('heroicon-o-eye-slash')
                                        ->label('Basculer la visibilité')
                                        ->action(function (array $arguments, Builder $component): void {
                                            $state = $component->getState();
                                            $itemId = $arguments['item'];

                                            // Basculer is_hidden
                                            $currentValue = $state[$itemId]['data']['is_hidden'] ?? false;
                                            $state[$itemId]['data']['is_hidden'] = !$currentValue;

                                            $component->state($state);
                                        }),
                                ])
                                ->blocks([
                                    Block::make('hero')
                                        ->label('Bannière Hero')
                                        ->label(function (?array $state): string {
                                            $name = "BENNIERE HERO";
                                            if ($state === null) {
                                                return $name;
                                            }
                                            $anchor = $state['anchor'] ?? 'X';
                                            return sprintf('%s | Ancre : [%s]', $name, $anchor);
                                        })
                                        ->schema([
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            ...self::getBase(),
                                                            self::getTitleRichEditor('title', 'Titre'),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                            self::getActionsEditor(),

                                                        ]),
                                                    self::getTabStylesHero(),
                                                ]),
                                        ])
                                        ->preview('components.blocks.hero'),

                                    Block::make('content')
                                        ->label('Contenu')
                                        ->label(function (?array $state): string {
                                            $name = "CONTENU";
                                            if ($state === null) {
                                                return $name;
                                            }
                                            $anchor = $state['anchor'] ?? 'X';
                                            return sprintf('%s | Ancre : [%s]', $name, $anchor);
                                        })
                                        ->schema([
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            ...self::getBase(),
                                                            self::getTitleRichEditor('title', 'Titre'),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                            Grid::make([
                                                                'default' => 1,
                                                                'sm' => 1,
                                                                'md' => 2
                                                            ])->schema([
                                                                self::getFullRichEditor('texts', 'Texte principal'),
                                                                self::getGridPhoto()
                                                            ]),
                                                            Toggle::make('left_image')
                                                                ->label('Image à gauche')
                                                                ->helperText('Par défaut, l\'image est à droite')
                                                                ->default(false)
                                                        ]),
                                                    // self::getTabImageFond(),
                                                    self::getTabStylesContent(),
                                                ]),
                                        ])
                                        ->preview('components.blocks.content'),
                                    Block::make('new-content')
                                        ->label('Contenu multiple')
                                        ->label(function (?array $state): string {
                                            $name = "CONTENU MULTIPLE";
                                            if ($state === null) {
                                                return $name;
                                            }
                                            $anchor = $state['anchor'] ?? 'X';
                                            return sprintf('%s | Ancre : [%s]', $name, $anchor);
                                        })
                                        ->schema([
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            ...self::getBase(),
                                                            self::getTitleRichEditor('title', 'Titre'),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                            Builder::make('subcontents')
                                                                ->label('Contenu de la section')
                                                                ->collapsible()
                                                                ->cloneable()
                                                                ->grow()
                                                                ->extraItemActions([
                                                                    Action::make('toggleVisibility')
                                                                        ->icon('heroicon-o-eye-slash')
                                                                        ->label('Basculer la visibilité')
                                                                        ->action(function (array $arguments, Builder $component): void {
                                                                            $state = $component->getState();
                                                                            $itemId = $arguments['item'];

                                                                            // Basculer is_hidden
                                                                            $currentValue = $state[$itemId]['data']['is_hidden'] ?? false;
                                                                            $state[$itemId]['data']['is_hidden'] = !$currentValue;

                                                                            $component->state($state);
                                                                        }),
                                                                ])
                                                                ->blocks([
                                                                    Block::make('texte-photo')
                                                                        ->label('Texte + Photo')
                                                                        ->schema([
                                                                            Grid::make([
                                                                                'default' => 1,
                                                                                'sm' => 1,
                                                                                'md' => 2
                                                                            ])->schema([
                                                                                self::getFullRichEditor('texts', 'Texte principal'),
                                                                                self::getGridPhoto()
                                                                            ]),
                                                                        ]),

                                                                    Block::make('photo-texte')
                                                                        ->label('Photo + Texte')
                                                                        ->schema([
                                                                            Grid::make([
                                                                                'default' => 1,
                                                                                'sm' => 1,
                                                                                'md' => 2
                                                                            ])->schema([
                                                                                self::getGridPhoto(),
                                                                                self::getFullRichEditor('texts', 'Texte principal')
                                                                            ]),
                                                                        ]),

                                                                    Block::make('texte-texte')
                                                                        ->label('Texte + Texte')
                                                                        ->schema([
                                                                            Grid::make([
                                                                                'default' => 1,
                                                                                'sm' => 1,
                                                                                'md' => 2
                                                                            ])->schema([
                                                                                self::getFullRichEditor('texts', 'Texte principal'),
                                                                                self::getFullRichEditor('secondary_text', 'Texte secondaire')
                                                                            ]),
                                                                        ]),
                                                                ]),
                                                        ]),
                                                    self::getTabStylesContent(),
                                                ]),
                                        ])
                                        ->preview('components.blocks.new-content'),
                                ]),
                        ])->columnSpan(3),
                    Section::make('Détails de la page')
                        ->schema([
                            TextInput::make('titre')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),
                            TextInput::make('slug')
                                ->required()
                                ->suffixAction(self::getPreviewAction()),
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
                            Toggle::make('has_form')
                                ->label('Afficher le formulaire de contact')
                                ->helperText('Active l\'affichage du formulaire de contact sur cette page'),
                            Textarea::make('meta_description')
                                ->label('Description SEO')
                                ->helperText('Description pour les moteurs de recherche (160 caractères max)')
                                ->rows(3)
                                ->maxLength(160),
                            TextInput::make('meta_keywords')
                                ->label('Mots-clés SEO')
                                ->helperText('Mots-clés séparés par des virgules pour le référencement'),
                            DateTimePicker::make('published_at')
                                ->label('Date de publication'),
                        ])
                        ->footerActions([
                            self::getPreviewAction()
                                ->label('Prévisualiser la page')
                                ->button()
                                ->color('info')
                                ->size('sm'),
                        ])
                        ->columnSpan(1),

                ]),
            ]);
    }

    public static function getPreviewAction(): Action
    {
        return Action::make('preview')
            ->icon('heroicon-o-eye')
            ->tooltip('Prévisualiser la page')
            ->url(fn($get) => $get('slug') ? route('page', ['slug' => $get('slug')]) : null)
            ->openUrlInNewTab()
            ->color('gray')
            ->disabled(fn($get) => $get('status') !== 'published');
    }

    public static function getBase(): array
    {
        return [
            Hidden::make('block_id')
                ->default(fn() => (string) \Illuminate\Support\Str::uuid()),
            TextInput::make('anchor')
                ->label('Ancre (ID)')
                ->helperText('Identifiant unique pour créer un lien vers cette section')
                ->placeholder('ex: ma-section'),

        ];
    }

    public static function getFullRichEditor(string $key, string $label): RichEditor
    {
        // Ajouter automatiquement le préfixe html_ si pas déjà présent
        $fieldName = str_starts_with($key, 'html_') ? $key : 'html_' . $key;
        
        return RichEditor::make($fieldName)
            ->label($label)
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'pageLink'],
                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                ['blockquote', 'codeBlock', 'bulletList', 'customOrderedList'],
                ['undo', 'redo', 'clearFormatting'],
            ])
            ->plugins([PageLinkPlugin::make(), OrderedListPlugin::make()]);
    }

    public static function getTitleRichEditor(string $key, string $label): RichEditor
    {
        // Ajouter automatiquement le préfixe html_ si pas déjà présent
        $fieldName = str_starts_with($key, 'html_') ? $key : 'html_' . $key;
        
        return  RichEditor::make($fieldName)
            ->label($label)
            ->required()
            ->toolbarButtons([
                'bold', 'clearFormatting'
            ]);
    }

    public static function getActionsEditor(): Repeater
    {
        return Repeater::make('boutons')
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
                        'tertiary' => 'Tertiaire',
                    ])
                    ->default('primary')
                    ->required(),
                Section::make([
                    Select::make('type_lien')
                        ->label('Type de lien')
                        ->options([
                            'page' => 'Page du site',
                            'externe' => 'URL externe',
                        ])
                        ->default('page')
                        ->live()
                        ->required(),
                    Select::make('page_id')
                        ->label('Page')
                        ->options([
                            'same_page' => 'Rester sur la page',
                            ...\App\Models\Page::pluck('titre', 'slug')->toArray()
                        ])
                        ->default('same_page')
                        ->visible(fn($get) => $get('type_lien') === 'page')
                        ->live()
                        ->required(fn($get) => $get('type_lien') === 'page'),
                    TextInput::make('ancre')
                        ->label('Ancre')
                        ->placeholder('ex: #ma-section')
                        ->visible(fn($get) => $get('type_lien') === 'page')
                        ->helperText('Optionnel : section spécifique de la page'),
                    TextInput::make('url_externe')
                        ->label('URL externe')
                        ->placeholder('https://exemple.com')
                        ->visible(fn($get) => $get('type_lien') === 'externe'),

                ])->contained(false)->columns(3),

            ])
            ->collapsible()
            ->maxItems(4)
            ->columns(2)
            ->collapsed()
            ->addActionLabel('Ajouter un bouton')
            ->itemLabel(fn(array $state): ?string => $state['texte'] ?? null);
    }

    public static function getGridBgImage(): Grid
    {
        return Grid::make(1)
            ->statePath('background_datas')
            ->schema([
                Select::make('mode')
                    ->label('Mode de fond')
                    ->options([
                        'aucun' => 'Aucun',
                        'image' => 'Image de fond et/ou dégradés',
                        'filtre' => 'Filtre',
                    ])
                    ->default('aucun')
                    ->live()
                    ->required(),
                    
                OptimizingFileUpload::make('image_background')
                    ->label('Image de fond')
                    ->disk('public')
                    ->directory('images-bg')
                    ->optimize('webp')
                    ->maxImageWidth(1920)
                    ->maxImageHeight(1080)
                    ->jpegQuality(85)
                    ->image()
                    ->imageEditor()
                    ->autoHelperText()
                    ->visible(fn($get) => $get('mode') === 'image'),
                    
                Select::make('couche_blanc')
                    ->label('Couche de blanc')
                    ->helperText('La couche de blanc permet d\'ajuster la lisibilité du texte sur des images')
                    ->options([
                        'aucun' => 'Aucun',
                        'bg-gradient-to-b from-white/30 to-white/70' => 'Normal',
                        'bg-gradient-to-b from-white/50 to-white/100' => 'Fort',
                        'bg-gradient-to-l from-white/80 to-white/70' => 'Dense',
                    ])
                    ->default('aucun')
                    ->visible(fn($get) => $get('mode') === 'image'),
                    
                Select::make('gradients')
                    ->label('dégradés de couleurs')
                    ->options([
                        'bg-gradient-to-r from-primary-500 to-secondary-500' => 'Primaire vers secondaire (horizontal)',
                        'bg-gradient-to-b from-primary-500 to-secondary-500' => 'Primaire vers secondaire (vertical)',
                        'bg-gradient-to-br from-primary-500 via-secondary-500 to-tertiary-500' => 'Primaire → Secondaire → Tertiaire (diagonal)',
                        'bg-gradient-to-t from-secondary-500 to-primary-500' => 'Secondaire vers primaire (montant)',
                        'bg-gradient-to-bl from-tertiary-500 via-primary-500 to-secondary-500' => 'Tertiaire → Primaire → Secondaire (diagonal inverse)',
                        'aucun' => 'Aucun',
                    ])
                    ->default('aucun')
                    ->visible(fn($get) => $get('mode') === 'image'),
                    
                Select::make('mask')
                    ->label('Masque')
                    ->default('hero-mask-1')
                    ->options([
                        'hero-mask-1' => 'M1',
                        'hero-mask-2' => 'M2',
                        'hero-mask-3' => 'M3',
                        'hero-mask-4' => 'M4',
                    ])
                    ->visible(fn($get) => $get('mode') === 'filtre')
                    ->required(fn($get) => $get('mode') === 'filtre'),
                    
                Select::make('mask_color')
                    ->label('Couleur du masque')
                    ->options([
                        'bg-primary-500' => 'Primaire',
                        'bg-primary-200' => 'Primaire Claire',
                        'bg-secondary-500' => 'Secondaire',
                        'bg-secondary-200' => 'Secondaire Claire',
                        'bg-tertiary-500' => 'Tertiaire',
                        'bg-tertiary-200' => 'Tertiaire Claire',
                    ])
                    ->visible(fn($get) => $get('mode') === 'filtre'),
            ])->columnSpan(1);
    }

    public static function getGridStyles(): Grid
    {
        return  Grid::make(1)
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
                Toggle::make('minH70vh')
                    ->label('Hauteur minimale 70vh')
                    ->helperText('Définit une hauteur minimale de 70% de la hauteur de l\'écran')
                    ->default(false),
                Toggle::make('afficher_separateur')
                    ->label('Afficher un séparateur')
                    ->helperText('Affiche une ligne de séparation sous le contenu')
                    ->default(false),
                Toggle::make('animate')
                    ->label('Activer les animations')
                    ->helperText('Active les animations d\'apparition des éléments lors du scroll')
                    ->default(true),
                Toggle::make('is_hidden')
                    ->label('Cacher temporairement ce bloc')
                    ->default(false),
            ])
            ->columnSpan(1);
    }



    public static function getGridPhoto(): Grid
    {
        return Grid::make(1)
            ->statePath('photo_config')
            ->schema([
                OptimizingFileUpload::make('image_url')
                    ->label('Photo')
                    ->optimize('webp')
                    ->maxImageWidth(800)
                    ->autoHelperText()
                    ->directory('image-photos')
                    ->maxSize(5000),
                Select::make('display_type')
                    ->label('Type d\'affichage')
                    ->selectablePlaceholder(false)
                    ->options([
                        'mask_brush_square' => 'Trait de pinceaux carré',
                        'mask_brush_169' => 'Trait de pinceaux 16:9',
                        'full_cover' => 'Couvrant',
                    ])
                    ->default('mask_brush_square')
                    ->live(),
                Select::make('position')
                    ->label('Position de la photo')
                    ->options([
                        'center' => 'Centre',
                        'top' => 'Haut',
                        'bottom' => 'Bas',
                        'left' => 'Gauche',
                        'right' => 'Droite',
                        'top-left' => 'Haut gauche',
                        'top-right' => 'Haut droite',
                        'bottom-left' => 'Bas gauche',
                        'bottom-right' => 'Bas droite',
                    ])
                    ->default('center')
                    ->helperText('Position de recadrage de l\'image')
                    ->visible(fn($get) => $get('display_type') === 'full_cover'),
            ])->columnSpan(1);
    }

    public static function getTabStylesHero(): Tab
    {
        return Tab::make('Style')
            ->schema([
                Grid::make([
                    'default' => 2,
                    'sm' => 1,
                    'md' => 2
                ])->schema([
                    Grid::make(1)
                        ->statePath('ambiance')
                        ->schema([
                            Select::make('couleur_primaire')
                                ->label('Couleur du titre')
                                ->options([
                                    'primary' => 'Primaire',
                                    'secondary' => 'Secondaire',
                                    'primary-brush' => 'Pinceau primaire',
                                    'secondary-brush' => 'Pinceau secondaire',
                                ])
                                ->default('secondary'),
                            Toggle::make('animate')
                                ->label('Activer les animations')
                                ->helperText('Active les animations d\'apparition des éléments lors du scroll')
                                ->default(true),
                            Toggle::make('is_hidden')
                                ->label('Cacher temporairement ce bloc')
                                ->default(false),
                            Toggle::make('minH70vh')
                                ->label('Hauteur minimale 70vh')
                                ->helperText('Active une hauteur minimale de 70% de la hauteur de l\'écran')
                                ->default(true),
                        ])
                        ->columnSpan(1),
                    static::getGridBgImage(),
                ])

            ]);
    }

    public static function getTabStylesContent(): Tab
    {
        return Tab::make('Style')
            ->schema([
                Grid::make([
                    'default' => 2,
                    'sm' => 1,
                    'md' => 2
                ])->schema([
                    Grid::make(1)
                        ->statePath('ambiance')
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
                            Toggle::make('animate')
                                ->label('Activer les animations')
                                ->helperText('Active les animations d\'apparition des éléments lors du scroll')
                                ->default(true),
                            Toggle::make('is_hidden')
                                ->label('Cacher temporairement ce bloc')
                                ->default(false),
                            Toggle::make('minH70vh')
                                ->label('Hauteur minimale 70vh')
                                ->helperText('Active une hauteur minimale de 70% de la hauteur de l\'écran')
                                ->default(false),
                        ])
                        ->columnSpan(1),
                    static::getGridBgImage(),
                ])

            ]);
    }
}
