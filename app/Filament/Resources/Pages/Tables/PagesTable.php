<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Filament\Actions\DuplicatePageAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Slug copié!')
                    ->copyMessageDuration(1500),
                IconColumn::make('is_homepage')
                    ->label('Page d\'accueil')
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable()
                    ->updateStateUsing(function ($record, $state) {
                        if ($state) {
                            // Si on active is_homepage, désactiver toutes les autres
                            \App\Models\Page::where('id', '!=', $record->id)
                                ->update(['is_homepage' => false]);
                        }
                        $record->update(['is_homepage' => $state]);
                        return $state;
                    }),
                IconColumn::make('is_in_header')
                    ->label('Header')
                    ->boolean()
                    ->trueIcon('heroicon-o-bars-3')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('primary')
                    ->falseColor('gray')
                    ->toggleable()
                    ->updateStateUsing(function ($record, $state) {
                        $record->update(['is_in_header' => $state]);
                        return $state;
                    }),
                IconColumn::make('is_in_footer')
                    ->label('Footer')
                    ->boolean()
                    ->trueIcon('heroicon-o-bars-2')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->toggleable()
                    ->updateStateUsing(function ($record, $state) {
                        $record->update(['is_in_footer' => $state]);
                        return $state;
                    }),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                    }),
                TextColumn::make('published_at')
                    ->label('Publié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DuplicatePageAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
