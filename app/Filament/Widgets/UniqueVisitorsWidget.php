<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Shetabit\Visitor\Models\Visit;
use Illuminate\Support\Facades\DB;

class UniqueVisitorsWidget extends ChartWidget
{
    protected ?string $heading = 'Visiteurs uniques par navigateur';
    
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        // Obtenir les visiteurs uniques par navigateur
        $browserStats = Visit::select('browser', DB::raw('COUNT(DISTINCT ip) as unique_visitors'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('unique_visitors')
            ->limit(5)
            ->get();

        // S'assurer qu'on a des données à afficher
        if ($browserStats->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#E5E7EB'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['Aucune donnée'],
            ];
        }

        $labels = [];
        $data = [];
        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];

        foreach ($browserStats as $index => $stat) {
            $labels[] = $stat->browser;
            $data[] = $stat->unique_visitors;
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
