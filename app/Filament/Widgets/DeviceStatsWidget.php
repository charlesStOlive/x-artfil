<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Shetabit\Visitor\Models\Visit;
use Illuminate\Support\Facades\DB;

class DeviceStatsWidget extends ChartWidget
{
    protected ?string $heading = 'Visites par appareil';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $deviceStats = Visit::select('device', DB::raw('count(*) as count'))
            ->whereNotNull('device')
            ->groupBy('device')
            ->get();

        // S'assurer qu'on a des données à afficher
        if ($deviceStats->isEmpty()) {
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

        foreach ($deviceStats as $index => $stat) {
            $labels[] = ucfirst($stat->device);
            $data[] = $stat->count;
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
