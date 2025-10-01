<?php

namespace App\Filament\Actions;

use App\Models\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class DuplicatePageAction
{
    public static function make(): Action
    {
        return Action::make('duplicate')
            ->label('Dupliquer')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->form([
                TextInput::make('titre')
                    ->label('Nouveau titre')
                    ->required()
                    ->default(fn (Page $record) => $record->titre . ' - Copie'),
                TextInput::make('slug')
                    ->label('Nouveau slug')
                    ->required()
                    ->default(fn (Page $record) => $record->slug . '-copie')
                    ->unique(Page::class, 'slug'),
            ])
            ->action(function (array $data, Page $record): void {
                $newPage = $record->replicate();
                $newPage->titre = $data['titre'];
                $newPage->slug = $data['slug'];
                $newPage->is_homepage = false; // La copie ne peut pas être homepage
                $newPage->status = 'draft'; // La copie est toujours en brouillon
                $newPage->published_at = null; // Retirer la date de publication
                $newPage->save();

                Notification::make()
                    ->title('Page dupliquée avec succès')
                    ->success()
                    ->send();
            });
    }
}