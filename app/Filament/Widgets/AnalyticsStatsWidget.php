<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Shetabit\Visitor\Models\Visit;
use Illuminate\Support\Facades\DB;

class AnalyticsStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getStats(): array
    {
        // Statistiques d'aujourd'hui
        $todayVisits = Visit::whereDate('created_at', today())->count();
        $yesterdayVisits = Visit::whereDate('created_at', today()->subDay())->count();
        $todayChange = $yesterdayVisits > 0 ? (($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100 : 0;

        // Statistiques de cette semaine
        $thisWeekVisits = Visit::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        $lastWeekVisits = Visit::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->count();
        $weekChange = $lastWeekVisits > 0 ? (($thisWeekVisits - $lastWeekVisits) / $lastWeekVisits) * 100 : 0;

        // Visiteurs uniques aujourd'hui
        $uniqueVisitorsToday = Visit::whereDate('created_at', today())
            ->distinct('ip')
            ->count();

        // Pages les plus visitées aujourd'hui
        $topPageToday = Visit::whereDate('created_at', today())
            ->select('request', DB::raw('count(*) as visits'))
            ->groupBy('request')
            ->orderByDesc('visits')
            ->first();

        // Formater le nom de la page
        $pageName = 'Aucune donnée';
        $pageDescription = 'Aucune visite aujourd\'hui';
        
        if ($topPageToday) {
            $path = $topPageToday->request;
            if ($path === '/' || $path === '') {
                $pageName = 'Page d\'accueil';
            } else {
                $pageName = ucfirst(trim($path, '/'));
                // Remplacer les tirets par des espaces et capitaliser
                $pageName = ucwords(str_replace(['-', '_'], ' ', $pageName));
            }
            $pageDescription = $topPageToday->visits . ' visite' . ($topPageToday->visits > 1 ? 's' : '') . ' aujourd\'hui';
        }

        return [
            Stat::make('Visites aujourd\'hui', $todayVisits)
                ->description($todayChange >= 0 ? 
                    '+' . number_format($todayChange, 1) . '% vs hier' : 
                    number_format($todayChange, 1) . '% vs hier'
                )
                ->descriptionIcon($todayChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($todayChange >= 0 ? 'success' : 'danger')
                ->chart([7, 12, 15, 8, $todayVisits]),

            Stat::make('Visiteurs uniques', $uniqueVisitorsToday)
                ->description('Adresses IP distinctes aujourd\'hui')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([3, 5, 7, 4, $uniqueVisitorsToday]),

            Stat::make('Visites cette semaine', $thisWeekVisits)
                ->description($weekChange >= 0 ? 
                    '+' . number_format($weekChange, 1) . '% vs sem. dernière' : 
                    number_format($weekChange, 1) . '% vs sem. dernière'
                )
                ->descriptionIcon($weekChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($weekChange >= 0 ? 'success' : 'danger')
                ->chart($weekChange >= 0 ? [12, 18, 25, 20, $thisWeekVisits] : [$thisWeekVisits,20, 25, 18, 12]),

            Stat::make('Page populaire', $pageName)
                ->description($pageDescription)
                ->descriptionIcon('heroicon-m-eye')
                ->color('secondary')
                ->chart([1, 3, 2, 5, $topPageToday ? $topPageToday->visits : 0]),
        ];
    }
}
