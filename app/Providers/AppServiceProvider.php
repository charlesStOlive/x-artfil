<?php

namespace App\Providers;

use Filament\Schemas\Components\Grid;
use Illuminate\Support\ServiceProvider;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Forms\Components\OptimizingFileUpload;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Préserver le comportement v3 pour la visibilité des fichiers (si vous utilisez des disques non-locaux)
        OptimizingFileUpload::configureUsing(fn(OptimizingFileUpload $fileUpload) => $fileUpload
            ->visibility('public'));

        FileUpload::configureUsing(fn(FileUpload $fileUpload) => $fileUpload
            ->visibility('public'));

        ImageColumn::configureUsing(fn(ImageColumn $imageColumn) => $imageColumn
            ->visibility('public'));

        ImageEntry::configureUsing(fn(ImageEntry $imageEntry) => $imageEntry
            ->visibility('public'));

        // Préserver le comportement v3 pour les composants de layout
        Fieldset::configureUsing(fn(Fieldset $fieldset) => $fieldset
            ->columnSpanFull());

        Grid::configureUsing(fn(Grid $grid) => $grid
            ->columnSpanFull());

        Section::configureUsing(fn(Section $section) => $section
            ->columnSpanFull());
            
        Tab::configureUsing(fn(Tab $tab) => $tab
            ->columnSpanFull());


        // Partager les données de navigation avec toutes les vues
        $this->shareNavigationData();
    }

    /**
     * Partage les données de navigation avec toutes les vues
     */
    private function shareNavigationData(): void
    {
        view()->composer(['layouts.front', 'partials.header', 'partials.footer'], function ($view) {
            $headerPages = \App\Models\Page::where('is_in_header', true)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->get(['titre', 'slug']);

            $footerPages = \App\Models\Page::where('is_in_footer', true)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->get(['titre', 'slug']);

            $view->with([
                'headerPages' => $headerPages,
                'footerPages' => $footerPages,
            ]);
        });
    }
}
