<?php

namespace App\Services;

use Shetabit\Visitor\Models\Visit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Obtenir les statistiques générales
     */
    public function getGeneralStats(): array
    {
        return [
            'total_visits' => Visit::count(),
            'unique_visitors' => Visit::distinct('ip')->count(),
            'today_visits' => Visit::whereDate('created_at', today())->count(),
            'yesterday_visits' => Visit::whereDate('created_at', today()->subDay())->count(),
            'this_month_visits' => Visit::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'last_month_visits' => Visit::whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->count(),
        ];
    }

    /**
     * Obtenir les pages les plus visitées
     */
    public function getTopPages(int $limit = 10): array
    {
        return Visit::select('request', DB::raw('COUNT(*) as visits'))
            ->groupBy('request')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Obtenir les statistiques par appareil
     */
    public function getDeviceStats(): array
    {
        return Visit::select('device', DB::raw('COUNT(*) as count'))
            ->whereNotNull('device')
            ->groupBy('device')
            ->get()
            ->pluck('count', 'device')
            ->toArray();
    }

    /**
     * Obtenir les statistiques par navigateur
     */
    public function getBrowserStats(int $limit = 10): array
    {
        return Visit::select('browser', DB::raw('COUNT(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->pluck('count', 'browser')
            ->toArray();
    }

    /**
     * Obtenir les statistiques par OS
     */
    public function getPlatformStats(int $limit = 10): array
    {
        return Visit::select('platform', DB::raw('COUNT(*) as count'))
            ->whereNotNull('platform')
            ->groupBy('platform')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->pluck('count', 'platform')
            ->toArray();
    }

    /**
     * Obtenir les référents principaux
     */
    public function getTopReferrers(int $limit = 10): array
    {
        return Visit::select('referer', DB::raw('COUNT(*) as visits'))
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->groupBy('referer')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Obtenir les visites par heure (pour aujourd'hui)
     */
    public function getHourlyVisits(): array
    {
        $visits = Visit::selectRaw('HOUR(created_at) as hour, COUNT(*) as visits')
            ->whereDate('created_at', today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('visits', 'hour')
            ->toArray();

        // Remplir les heures manquantes avec 0
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyData[$i] = $visits[$i] ?? 0;
        }

        return $hourlyData;
    }

    /**
     * Obtenir les visites des X derniers jours
     */
    public function getDailyVisits(int $days = 30): array
    {
        $visits = Visit::selectRaw('DATE(created_at) as date, COUNT(*) as visits')
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('visits', 'date')
            ->toArray();

        // Remplir les jours manquants avec 0
        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyData[$date] = $visits[$date] ?? 0;
        }

        return $dailyData;
    }

    /**
     * Obtenir le taux de rebond (approximation basée sur les visites uniques par IP)
     */
    public function getBounceRate(): float
    {
        $totalVisitors = Visit::distinct('ip')->count();
        if ($totalVisitors === 0) return 0;

        $singlePageVisitors = Visit::select('ip')
            ->groupBy('ip')
            ->havingRaw('COUNT(*) = 1')
            ->count();

        return ($singlePageVisitors / $totalVisitors) * 100;
    }

    /**
     * Obtenir les visiteurs les plus actifs
     */
    public function getTopVisitors(int $limit = 10): array
    {
        return Visit::select('ip', DB::raw('COUNT(*) as visits'))
            ->groupBy('ip')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Obtenir les statistiques de croissance
     */
    public function getGrowthStats(): array
    {
        $thisMonth = Visit::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $lastMonth = Visit::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $monthlyGrowth = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        $thisWeek = Visit::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        $lastWeek = Visit::whereBetween('created_at', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek()
        ])->count();

        $weeklyGrowth = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : 0;

        return [
            'monthly_growth' => $monthlyGrowth,
            'weekly_growth' => $weeklyGrowth,
            'this_month_visits' => $thisMonth,
            'last_month_visits' => $lastMonth,
            'this_week_visits' => $thisWeek,
            'last_week_visits' => $lastWeek,
        ];
    }
}