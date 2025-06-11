<?php

namespace App\Services;

use App\Models\AchievementModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AchievementStatisticsService
{
    public static function getAchievementsByType()
    {
        $defaultTypes = [
            'Juara' => 0,
            'Penghargaan' => 0,
            'Sertifikasi' => 0,
            'Publikasi' => 0,
            'Magang' => 0,
            'Kompetisi' => 0,
            'Lainnya' => 0
        ];
        
        $achievementsData = AchievementModel::select('type', DB::raw('count(*) as total'))
            ->where('status', 'verified')
            ->groupBy('type')
            ->get()
            ->keyBy('type')
            ->map(function ($item) {
                return (int) $item->total;
            })
            ->toArray();
            
        $mergedData = array_merge($defaultTypes, $achievementsData);
        
        $result = [];
        foreach ($mergedData as $type => $total) {
            if ($total > 0 || array_key_exists($type, $achievementsData)) {
                $result[] = [
                    'type' => $type,
                    'total' => $total
                ];
            }
        }
        
        return $result;
    }

    public static function getAchievementsByMonth()
    {
        $currentYear = Carbon::now()->year;
        
        $indonesianMonths = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = [
                'month' => $indonesianMonths[$i],
                'total' => 0
            ];
        }
        
        $achievementsByMonth = AchievementModel::select(
                DB::raw('MONTH(date) as month'), 
                DB::raw('count(*) as total')
            )
            ->where('status', 'verified')
            ->whereYear('date', $currentYear)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();
        
        foreach ($achievementsByMonth as $item) {
            if (isset($months[$item->month])) {
                $months[$item->month]['total'] = (int) $item->total;
            }
        }
        
        return array_values($months);
    }

    public static function getTotalAchievements()
    {
        return AchievementModel::where('status', 'verified')->count();
    }

    public static function getAchievementsByLevel(string $level)
    {
        return AchievementModel::where('status', 'verified')
            ->where('level', $level)
            ->count();
    }

    public static function getAchievementGrowth(int $months = 1)
    {
        $now = Carbon::now();
        $currentPeriodStart = $now->copy()->subMonths($months);
        
        $currentCount = AchievementModel::where('status', 'verified')
            ->whereDate('date', '>=', $currentPeriodStart)
            ->count();
            
        $previousPeriodStart = $currentPeriodStart->copy()->subMonths($months);
        $previousCount = AchievementModel::where('status', 'verified')
            ->whereDate('date', '>=', $previousPeriodStart)
            ->whereDate('date', '<', $currentPeriodStart)
            ->count();
            
        $growthPercentage = 0;
        if ($previousCount > 0) {
            $growthPercentage = round((($currentCount - $previousCount) / $previousCount) * 100);
        } elseif ($currentCount > 0) {
            $growthPercentage = 100; 
        }
        
        return [
            'current' => $currentCount,
            'previous' => $previousCount,
            'percentage' => $growthPercentage,
            'trend' => $growthPercentage >= 0 ? 'up' : 'down'
        ];
    }

    public static function getStatisticsData()
    {
        $totalAchievements = self::getTotalAchievements();
        $internationalCount = self::getAchievementsByLevel('Internasional');
        $nationalCount = self::getAchievementsByLevel('Nasional');
        $growthData = self::getAchievementGrowth(3); 
        
        return [
            'byType' => self::getAchievementsByType(),
            'byMonth' => self::getAchievementsByMonth(),
            'summary' => [
                'total' => $totalAchievements,
                'international' => $internationalCount,
                'national' => $nationalCount,
                'growth' => $growthData
            ]
        ];
    }
}
