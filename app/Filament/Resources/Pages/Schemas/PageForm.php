<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use App\Filament\Components\OptimizingFileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;

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
                                                            TextInput::make('title')
                                                                ->label('Titre')
                                                                ->required(),
                                                            Textarea::make('description')
                                                                ->label('Description'),
                                                        ]),
                                                    Tab::make('Image de fond')
                                                        ->schema([
                                                            OptimizingFileUpload::make('background_image')
                                                                ->label('Image de fond')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(1920)
                                                                ->maxImageHeight(1080)
                                                                ->jpegQuality(85)             
                                                                ->image()
                                                                ->imageEditor()
                                                                ->autoHelperText(),
                                                        ]),
                                                ]),
                                        ])
                                        ->preview('filament.content.block-previews.hero'),

                                    Block::make('testimonial')
                                        ->label('Témoignage')
                                        ->schema([
                                            Hidden::make('block_id')
                                                ->default(fn() => (string) \Illuminate\Support\Str::uuid()),
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('Contenu')
                                                        ->schema([
                                                            TextInput::make('title')
                                                                ->label('Nom de l\'auteur')
                                                                ->required(),
                                                            RichEditor::make('description')
                                                                ->label('Témoignage')
                                                                ->required()
                                                                ->toolbarButtons([
                                                                    'bold', 'italic', 'link', 'bulletList', 'orderedList'
                                                                ]),
                                                        ]),
                                                    Tab::make('Photo')
                                                        ->schema([
                                                            OptimizingFileUpload::make('author_image')
                                                                ->label('Photo de l\'auteur')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(500)
                                                                ->maxImageHeight(500)
                                                                ->avatar()
                                                                ->autoHelperText()
                                                                ->directory('testimonials')
                                                                ->maxSize(5000),
                                                        ]),
                                                ]),
                                        ])
                                        ->preview('filament.content.block-previews.testimonial'),

                                    Block::make('text-photo')
                                        ->label('Texte + Photo')
                                        ->schema([
                                            Hidden::make('block_id')
                                                ->default(fn() => (string) \Illuminate\Support\Str::uuid()),
                                            Tabs::make('Tabs')
                                                ->tabs([
                                                    Tab::make('tab_content')
                                                        ->schema([
                                                            RichEditor::make('text')
                                                                ->label('Texte')
                                                                ->required()
                                                                ->toolbarButtons([
                                                                    'bold', 'italic', 'underline', 'link', 'bulletList', 'orderedList', 'blockquote'
                                                                ]),
                                                            Select::make('layout')
                                                                ->label('Position de l\'image')
                                                                ->options([
                                                                    'left' => 'Image à gauche',
                                                                    'right' => 'Image à droite',
                                                                ])
                                                                ->default('left')
                                                                ->required(),
                                                        ]),
                                                    Tab::make('tab_image')
                                                        ->schema([
                                                            OptimizingFileUpload::make('single_image')
                                                                ->label('Photo')
                                                                ->optimize('webp')
                                                                ->maxImageWidth(1200)
                                                                ->image()
                                                                ->autoHelperText()
                                                                ->directory('content')
                                                                ->maxSize(5000)
                                                                ->imageEditor(),
                                                        ]),
                                                ]),
                                        ])
                                        ->preview('filament.content.block-previews.text-photo'),
                                ]),
                        ])->columnSpan(2),
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
                            DateTimePicker::make('published_at')
                                ->label('Date de publication'),
                        ])->columnSpan(1),
                    Section::make('Options d\'affichage')
                        ->schema([
                            Toggle::make('is_homepage')
                                ->label('Page d\'accueil')
                                ->helperText('Une seule page peut être définie comme page d\'accueil'),
                            Toggle::make('is_in_header')
                                ->label('Afficher dans le header'),
                            Toggle::make('is_in_footer')
                                ->label('Afficher dans le footer'),
                            TextInput::make('key_word')
                                ->label('Mots-clés SEO'),
                        ])->columnSpan(1),
                ]),
            ]);
    }
}
