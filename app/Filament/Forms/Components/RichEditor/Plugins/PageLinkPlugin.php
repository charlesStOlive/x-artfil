<?php

namespace App\Filament\Forms\Components\RichEditor\Plugins;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Enums\Width;
use App\Models\Page;

class PageLinkPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @return array<\Tiptap\Core\Extension>
     */
    public function getTipTapPhpExtensions(): array
    {
        // Pas d'extension PHP TipTap nécessaire pour cette fonctionnalité
        return [];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        // Pas d'extension JS nécessaire, nous utilisons la fonctionnalité Link native
        return [];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('pageLink')
                ->action(arguments: '{ selectedText: $getEditor().state.doc.textBetween($getEditor().state.selection.from, $getEditor().state.selection.to) }')
                ->icon('heroicon-o-link')
                ->label('Lien vers page'),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [
            Action::make('pageLink')
                ->label('Créer un lien')
                ->modalHeading('Insérer un lien vers une page')
                ->modalWidth(Width::Medium)
                ->fillForm(function (array $arguments): array {
                    // Récupérer le texte sélectionné depuis l'éditeur
                    $selectedText = $arguments['selectedText'] ?? '';
                    $cleanText = trim($selectedText);
                    
                    return [
                        'text' => !empty($cleanText) ? $cleanText : 'Texte du lien',
                    ];
                })
                ->schema([
                    TextInput::make('text')
                        ->label('Texte du lien')
                        ->required()
                        ->placeholder('Texte à afficher'),
                    Select::make('type')
                        ->label('Type de lien')
                        ->options([
                            'page' => 'Page du site',
                            'anchor' => 'Ancre (même page)', 
                            'external' => 'URL externe',
                        ])
                        ->default('page')
                        ->live()
                        ->required(),
                    Select::make('page_slug')
                        ->label('Page')
                        ->options(function () {
                            return Page::where('status', 'published')
                                ->pluck('titre', 'slug')
                                ->toArray();
                        })
                        ->searchable()
                        ->required()
                        ->visible(fn ($get) => $get('type') === 'page'),
                    TextInput::make('anchor')
                        ->label('Ancre')
                        ->placeholder('ex: #ma-section')
                        ->required()
                        ->visible(fn ($get) => $get('type') === 'anchor'),
                    TextInput::make('external_url')
                        ->label('URL externe')
                        ->placeholder('https://exemple.com')
                        ->url()
                        ->required()
                        ->visible(fn ($get) => $get('type') === 'external'),
                ])
                ->action(function (array $arguments, array $data, RichEditor $component): void {
                    // Construire l'URL selon le type
                    $href = match($data['type']) {
                        'page' => '/' . $data['page_slug'],
                        'anchor' => $data['anchor'],
                        'external' => $data['external_url'],
                        default => '#'
                    };

                    // Préparer les attributs du lien
                    $linkAttributes = ['href' => $href];
                    if ($data['type'] === 'external') {
                        $linkAttributes['target'] = '_blank';
                        $linkAttributes['rel'] = 'noopener noreferrer';
                    }

                    $selectedText = trim($arguments['selectedText'] ?? '');
                    
                    if (empty($selectedText)) {
                        // Pas de texte sélectionné : insérer le lien complet avec le texte
                        $linkHtml = '<a href="' . htmlspecialchars($href) . '"';
                        if ($data['type'] === 'external') {
                            $linkHtml .= ' target="_blank" rel="noopener noreferrer"';
                        }
                        $linkHtml .= '>' . htmlspecialchars($data['text']) . '</a>';
                        
                        $component->runCommands([
                            EditorCommand::make('insertContent', arguments: [$linkHtml]),
                        ], editorSelection: $arguments['editorSelection']);
                    } else {
                        // Texte sélectionné : appliquer le lien au texte existant
                        $component->runCommands([
                            EditorCommand::make('setLink', arguments: [$linkAttributes]),
                        ], editorSelection: $arguments['editorSelection']);
                    }
                }),
        ];
    }
}