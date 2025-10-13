<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Shetabit\Visitor\Models\Visit;
use Illuminate\Support\Facades\DB;
use App\Services\FilamentColorHelper;

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
                    ],
                ],
                'labels' => ['Aucune donnée'],
            ];
        }

        $labels = [];
        $data = [];
        
        // Utilisation des couleurs Filament
        $primaryColor = FilamentColorHelper::getHexColor('primary');
        $secondaryColor = FilamentColorHelper::getHexColor('secondary');
        
        $colors = [
            $primaryColor, 
            $secondaryColor, 
            FilamentColorHelper::addTransparency($primaryColor, 0.8), 
            FilamentColorHelper::addTransparency($secondaryColor, 0.8), 
            FilamentColorHelper::addTransparency($primaryColor, 0.6)
        ];

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
