<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Models\Visit;
use App\Services\FilamentColorHelper;

class AnalyticsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Évolution des visites';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    protected ?string $maxHeight = '300px';
    
    public ?string $filter = '7days';



    protected function getData(): array
    {
        $activeFilter = $this->filter;
        
        if ($activeFilter === '6months') {
            return $this->getMonthlyData();
        }
        
        return $this->getDailyData();
    }
    
    private function getDailyData(): array
    {
        // Préparer les labels pour les 7 derniers jours
        $labels = [];
        $visits = [];
        $uniqueVisitors = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            
            // Visites totales pour ce jour
            $dayVisits = Visit::whereDate('created_at', $date)->count();
            $visits[] = $dayVisits;
            
            // Visiteurs uniques pour ce jour (IPs distinctes)
            $dayUniqueVisitors = Visit::whereDate('created_at', $date)
                ->distinct('ip')
                ->count();
            $uniqueVisitors[] = $dayUniqueVisitors;
        }

        // Récupération des couleurs Filament
        $primaryColor = FilamentColorHelper::getHexColor('primary');
        $secondaryColor = FilamentColorHelper::getHexColor('secondary');
        
        return [
            'datasets' => [
                [
                    'label' => 'Visites totales',
                    'data' => $visits,
                    'backgroundColor' => FilamentColorHelper::addTransparency($primaryColor, 0.8),
                    'borderColor' => $primaryColor,
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Visiteurs uniques',
                    'data' => $uniqueVisitors,
                    'backgroundColor' => FilamentColorHelper::addTransparency($secondaryColor, 0.8),
                    'borderColor' => $secondaryColor,
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    private function getMonthlyData(): array
    {
        // Préparer les labels pour les 6 derniers mois
        $labels = [];
        $visits = [];
        $uniqueVisitors = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            // Visites totales pour ce mois
            $monthVisits = Visit::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $visits[] = $monthVisits;
            
            // Visiteurs uniques pour ce mois (IPs distinctes)
            $monthUniqueVisitors = Visit::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->distinct('ip')
                ->count();
            $uniqueVisitors[] = $monthUniqueVisitors;
        }

        // Récupération des couleurs Filament
        $primaryColor = FilamentColorHelper::getHexColor('primary');
        $secondaryColor = FilamentColorHelper::getHexColor('secondary');
        
        return [
            'datasets' => [
                [
                    'label' => 'Visites totales',
                    'data' => $visits,
                    'backgroundColor' => FilamentColorHelper::addTransparency($primaryColor, 0.8),
                    'borderColor' => $primaryColor,
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Visiteurs uniques',
                    'data' => $uniqueVisitors,
                    'backgroundColor' => FilamentColorHelper::addTransparency($secondaryColor, 0.8),
                    'borderColor' => $secondaryColor,
                    'borderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }
    
    protected function getFilters(): ?array
    {
        return [
            '7days' => '7 derniers jours',
            '6months' => '6 derniers mois',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Visites vs Visiteurs uniques',
                ],
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Visites totales',
                    ],
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Visiteurs uniques',
                    ],
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
