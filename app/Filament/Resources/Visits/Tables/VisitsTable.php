<?php

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip')
                    ->label('Adresse IP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('request')
                    ->label('Page visitée')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('referer')
                    ->label('Référent')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('device')
                    ->label('Appareil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'desktop' => 'success',
                        'mobile' => 'info',
                        'tablet' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('platform')
                    ->label('OS')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('browser')
                    ->label('Navigateur')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date de visite')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Filter::make('today')
                    ->label("Aujourd'hui")
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today())),
                    
                Filter::make('yesterday')
                    ->label('Hier')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today()->subDay())),
                    
                Filter::make('this_week')
                    ->label('Cette semaine')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])),
                    
                Filter::make('this_month')
                    ->label('Ce mois')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)),
                        
                SelectFilter::make('device')
                    ->label('Type d\'appareil')
                    ->options([
                        'desktop' => 'Desktop',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablette',
                    ]),
            ])
            ->recordActions([
                // ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
