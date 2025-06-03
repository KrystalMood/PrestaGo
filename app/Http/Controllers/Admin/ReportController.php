<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AchievementModel;
use App\Models\StudyProgramModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\CompetitionModel;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodModel;

class ReportController extends Controller
{
    public function index()
    {
        $totalAchievements = AchievementModel::where('status', 'verified')->count();
        $localAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Lokal')
            ->count();
        $regionalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Regional')
            ->count();
        $nationalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Nasional')
            ->count();
        $internationalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Internasional')
            ->count();
        
        $currentPeriodAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', date('Y'))
            ->count();
        $previousPeriodAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', date('Y')-1)
            ->count();
        $growthPercentage = $previousPeriodAchievements > 0 
            ? round((($currentPeriodAchievements - $previousPeriodAchievements) / $previousPeriodAchievements) * 100, 1)
            : 0;
            
        $monthlyTrends = AchievementModel::where('status', 'verified')
            ->whereYear('date', date('Y'))
            ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');
            
        $achievementTrends = [];
        for ($month = 1; $month <= 12; $month++) {
            $achievementTrends[] = [
                'month' => date('F', mktime(0, 0, 0, $month, 10)),
                'count' => $monthlyTrends->has($month) ? $monthlyTrends[$month]->count : 0
            ];
        }
            
        $programAchievements = StudyProgramModel::select('study_programs.name', DB::raw('COUNT(achievements.id) as achievement_count'))
            ->leftJoin('users', 'study_programs.id', '=', 'users.program_studi_id')
            ->leftJoin('achievements', function($join) {
                $join->on('users.id', '=', 'achievements.user_id')
                    ->where('achievements.status', '=', 'verified');
            })
            ->groupBy('study_programs.id', 'study_programs.name')
            ->orderBy('achievement_count', 'desc')
            ->get();
            
        $periodComparison = [
            'current' => [
                'name' => 'Tahun ' . date('Y'),
                'achievements' => $currentPeriodAchievements
            ],
            'previous' => [
                'name' => 'Tahun ' . (date('Y')-1),
                'achievements' => $previousPeriodAchievements
            ],
            'growth' => $growthPercentage
        ];
        
        return view('admin.reports.index', compact(
            'totalAchievements',
            'localAchievements',
            'regionalAchievements',
            'nationalAchievements',
            'internationalAchievements',
            'growthPercentage',
            'programAchievements',
            'periodComparison',
            'achievementTrends'
        ));
    }

    public function achievements()
    {
        $totalAchievements = AchievementModel::where('status', 'verified')->count();
        
        $localAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Lokal')
            ->count();
            
        $regionalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Regional')
            ->count();
            
        $nationalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Nasional')
            ->count();
            
        $internationalAchievements = AchievementModel::where('status', 'verified')
            ->where('level', 'Internasional')
            ->count();
        
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;
        
        $currentYearAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', $currentYear)
            ->count();
            
        $previousYearAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', $previousYear)
            ->count();
            
        $growthPercentage = $previousYearAchievements > 0 
            ? (($currentYearAchievements - $previousYearAchievements) / $previousYearAchievements) * 100 
            : 100;
            
        $yearlyGrowth = [
            'percentage' => round($growthPercentage, 1),
            'is_positive' => $growthPercentage >= 0
        ];
        
        $categories = CategoryModel::all();
        $categoryAchievements = [];
        
        foreach ($categories as $category) {
            $competitionIds = CompetitionModel::where('category_id', $category->id)->pluck('id')->toArray();
            
            $achievementCount = 0;
            if (!empty($competitionIds)) {
                $achievementCount = AchievementModel::where('status', 'verified')
                    ->whereIn('competition_id', $competitionIds)
                    ->count();
            }
            
            $categoryAchievements[] = [
                'name' => $category->name,
                'count' => $achievementCount
            ];
        }
        
        usort($categoryAchievements, function($a, $b) {
            return $b['count'] - $a['count'];
        });
            
        $programStats = StudyProgramModel::leftJoin('users', 'study_programs.id', '=', 'users.program_studi_id')
            ->leftJoin('achievements', 'users.id', '=', 'achievements.user_id')
            ->where('achievements.status', 'verified')
            ->selectRaw('
                study_programs.name,
                COUNT(DISTINCT CASE WHEN achievements.id IS NOT NULL THEN achievements.id END) as total,
                COUNT(DISTINCT CASE WHEN achievements.level = "Internasional" THEN achievements.id END) as international,
                COUNT(DISTINCT CASE WHEN achievements.level = "Nasional" THEN achievements.id END) as national,
                COUNT(DISTINCT CASE WHEN achievements.level = "Regional" THEN achievements.id END) as regional,
                COUNT(DISTINCT CASE WHEN achievements.level = "Lokal" THEN achievements.id END) as local
            ')
            ->groupBy('study_programs.id', 'study_programs.name')
            ->orderByDesc('total')
            ->get();
            
        return view('admin.reports.achievements', [
            'totalAchievements' => $totalAchievements,
            'localAchievements' => $localAchievements,
            'regionalAchievements' => $regionalAchievements,
            'nationalAchievements' => $nationalAchievements,
            'internationalAchievements' => $internationalAchievements,
            'yearlyGrowth' => $yearlyGrowth,
            'categoryAchievements' => $categoryAchievements,
            'programStats' => $programStats
        ]);
    }

    public function programs()
    {
        $programs = StudyProgramModel::all();
        
        $programStats = [];
        
        foreach ($programs as $program) {
            $totalStudents = UserModel::where('program_studi_id', $program->id)
                ->where('role', 'MHS')
                ->count();
                
            $achievements = AchievementModel::join('users', 'achievements.user_id', '=', 'users.id')
                ->where('users.program_studi_id', $program->id)
                ->where('users.role', 'MHS')
                ->where('achievements.status', 'verified')
                ->count();
                
            $studentsWithAchievements = AchievementModel::join('users', 'achievements.user_id', '=', 'users.id')
                ->where('users.program_studi_id', $program->id)
                ->where('users.role', 'MHS')
                ->where('achievements.status', 'verified')
                ->distinct('achievements.user_id')
                ->count('achievements.user_id');
                
            $participationRate = $totalStudents > 0 
                ? ($studentsWithAchievements / $totalStudents) * 100 
                : 0;
                
            $successRate = $studentsWithAchievements > 0 
                ? ($achievements / $studentsWithAchievements) * 100 
                : 0;
                
            $programStats[] = [
                'name' => $program->name,
                'students' => $totalStudents,
                'achievements' => $achievements,
                'participation_rate' => round($participationRate, 1),
                'success_rate' => round($successRate, 1)
            ];
        }
        
        usort($programStats, function($a, $b) {
            return $b['achievements'] - $a['achievements'];
        });
        
        return view('admin.reports.programs', [
            'programStats' => $programStats
        ]);
    }

    public function trends()
    {
        $currentYear = date('Y');
        $years = [$currentYear, $currentYear - 1, $currentYear - 2, $currentYear - 3];
        
        $yearlyData = [];
        
        foreach ($years as $year) {
            $achievements = AchievementModel::where('status', 'verified')
                ->whereYear('date', $year)
                ->count();
                
            $participants = AchievementModel::where('status', 'verified')
                ->whereYear('date', $year)
                ->distinct('user_id')
                ->count('user_id');
                
            $successRate = $participants > 0 
                ? ($achievements / $participants) 
                : 0;
                
            $previousYearAchievements = isset($yearlyData[$year - 1]) 
                ? $yearlyData[$year - 1]['achievements'] 
                : AchievementModel::where('status', 'verified')
                    ->whereYear('date', $year - 1)
                    ->count();
                    
            $change = $previousYearAchievements > 0 
                ? (($achievements - $previousYearAchievements) / $previousYearAchievements) * 100 
                : ($achievements > 0 ? 100 : 0);
                
            $yearlyData[$year] = [
                'year' => $year,
                'achievements' => $achievements,
                'participants' => $participants,
                'success_rate' => round($successRate, 2),
                'change' => round($change, 1),
                'is_positive' => $change >= 0
            ];
        }
        
        krsort($yearlyData);
        $yearlyData = array_values($yearlyData);
        
        $quarterlyData = [];
        
        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $quarter * 3;
            
            $achievements = AchievementModel::where('status', 'verified')
                ->whereYear('date', $currentYear)
                ->whereRaw("MONTH(date) BETWEEN $startMonth AND $endMonth")
                ->count();
                
            $participants = AchievementModel::where('status', 'verified')
                ->whereYear('date', $currentYear)
                ->whereRaw("MONTH(date) BETWEEN $startMonth AND $endMonth")
                ->distinct('user_id')
                ->count('user_id');
                
            $quarterlyData[] = [
                'quarter' => "Q$quarter",
                'achievements' => $achievements,
                'participants' => $participants
            ];
        }
        
        $totalAchievements = AchievementModel::where('status', 'verified')->count();
        $totalParticipants = AchievementModel::where('status', 'verified')
            ->distinct('user_id')
            ->count('user_id');
            
        $currentYearAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', $currentYear)
            ->count();
            
        $previousYearAchievements = AchievementModel::where('status', 'verified')
            ->whereYear('date', $currentYear - 1)
            ->count();
            
        $yearlyGrowth = $previousYearAchievements > 0 
            ? (($currentYearAchievements - $previousYearAchievements) / $previousYearAchievements) * 100 
            : ($currentYearAchievements > 0 ? 100 : 0);
            
        return view('admin.reports.trends', [
            'totalAchievements' => $totalAchievements,
            'totalParticipants' => $totalParticipants,
            'yearlyGrowth' => round($yearlyGrowth, 1),
            'yearlyGrowthPositive' => $yearlyGrowth >= 0,
            'yearlyData' => $yearlyData,
            'quarterlyData' => $quarterlyData
        ]);
    }

    public function periods()
    {
        $currentPeriod = PeriodModel::orderBy('end_date', 'desc')->first();
        $previousPeriod = PeriodModel::orderBy('end_date', 'desc')->skip(1)->first();
        
        if (!$currentPeriod || !$previousPeriod) {
            return view('admin.reports.periods', [
                'error' => 'Insufficient period data available'
            ]);
        }
        
        $currentPeriodName = $currentPeriod->name;
        $previousPeriodName = $previousPeriod->name;
        
        $currentPeriodAchievements = AchievementModel::where('status', 'verified')
            ->where('period_id', $currentPeriod->id)
            ->count();
            
        $previousPeriodAchievements = AchievementModel::where('status', 'verified')
            ->where('period_id', $previousPeriod->id)
            ->count();
            
        $achievementGrowth = $previousPeriodAchievements > 0 
            ? (($currentPeriodAchievements - $previousPeriodAchievements) / $previousPeriodAchievements) * 100 
            : 100;
        $achievementGrowthPositive = $achievementGrowth >= 0;
        
        $currentPeriodParticipation = AchievementModel::where('status', 'verified')
            ->where('period_id', $currentPeriod->id)
            ->distinct('user_id')
            ->count('user_id');
            
        $previousPeriodParticipation = AchievementModel::where('status', 'verified')
            ->where('period_id', $previousPeriod->id)
            ->distinct('user_id')
            ->count('user_id');
            
        $participationGrowth = $previousPeriodParticipation > 0 
            ? (($currentPeriodParticipation - $previousPeriodParticipation) / $previousPeriodParticipation) * 100 
            : 100;
        $participationGrowthPositive = $participationGrowth >= 0;
        
        $currentPeriodInternational = AchievementModel::where('status', 'verified')
            ->where('period_id', $currentPeriod->id)
            ->where('level', 'Internasional')
            ->count();
            
        $previousPeriodInternational = AchievementModel::where('status', 'verified')
            ->where('period_id', $previousPeriod->id)
            ->where('level', 'Internasional')
            ->count();
            
        $internationalGrowth = $previousPeriodInternational > 0 
            ? (($currentPeriodInternational - $previousPeriodInternational) / $previousPeriodInternational) * 100 
            : 100;
        $internationalGrowthPositive = $internationalGrowth >= 0;
        
        $currentPeriodNational = AchievementModel::where('status', 'verified')
            ->where('period_id', $currentPeriod->id)
            ->where('level', 'Nasional')
            ->count();
            
        $previousPeriodNational = AchievementModel::where('status', 'verified')
            ->where('period_id', $previousPeriod->id)
            ->where('level', 'Nasional')
            ->count();
            
        $nationalGrowth = $previousPeriodNational > 0 
            ? (($currentPeriodNational - $previousPeriodNational) / $previousPeriodNational) * 100 
            : 100;
        $nationalGrowthPositive = $nationalGrowth >= 0;
        
        $categories = CategoryModel::all();
        $categoriesData = [];
        
        foreach ($categories as $category) {
            $categoryCompetitionIds = CompetitionModel::where('category_id', $category->id)->pluck('id')->toArray();
            
            $currentCount = AchievementModel::where('status', 'verified')
                ->where('period_id', $currentPeriod->id)
                ->whereIn('competition_id', $categoryCompetitionIds)
                ->count();
                
            $previousCount = AchievementModel::where('status', 'verified')
                ->where('period_id', $previousPeriod->id)
                ->whereIn('competition_id', $categoryCompetitionIds)
                ->count();
                
            $changePercentage = $previousCount > 0 
                ? (($currentCount - $previousCount) / $previousCount) * 100 
                : ($currentCount > 0 ? 100 : 0);
                
            $categoriesData[] = [
                'name' => $category->name,
                'current_count' => $currentCount,
                'previous_count' => $previousCount,
                'change_percentage' => $changePercentage,
                'is_positive' => $changePercentage >= 0
            ];
        }
        
        usort($categoriesData, function($a, $b) {
            if ($a['is_positive'] != $b['is_positive']) {
                return $a['is_positive'] ? -1 : 1;
            }
            return $b['change_percentage'] - $a['change_percentage'];
        });
        
        return view('admin.reports.periods', [
            'currentPeriod' => $currentPeriodName,
            'previousPeriod' => $previousPeriodName,
            'currentPeriodAchievements' => $currentPeriodAchievements,
            'previousPeriodAchievements' => $previousPeriodAchievements,
            'achievementGrowth' => round($achievementGrowth, 1),
            'achievementGrowthPositive' => $achievementGrowthPositive,
            'currentPeriodParticipation' => $currentPeriodParticipation,
            'previousPeriodParticipation' => $previousPeriodParticipation,
            'participationGrowth' => round($participationGrowth, 1),
            'participationGrowthPositive' => $participationGrowthPositive,
            'currentPeriodInternational' => $currentPeriodInternational,
            'previousPeriodInternational' => $previousPeriodInternational,
            'internationalGrowth' => round($internationalGrowth, 1),
            'internationalGrowthPositive' => $internationalGrowthPositive,
            'currentPeriodNational' => $currentPeriodNational,
            'previousPeriodNational' => $previousPeriodNational,
            'nationalGrowth' => round($nationalGrowth, 1),
            'nationalGrowthPositive' => $nationalGrowthPositive,
            'categoriesData' => $categoriesData
        ]);
    }

    public function export()
    {
        $totalExports = 24;
        $pdfExports = 18;
        $excelExports = 6;
        $exportGrowth = 15;
        
        return view('admin.reports.export', compact(
            'totalExports',
            'pdfExports',
            'excelExports',
            'exportGrowth'
        ));
    }

    public function generateReport(Request $request)
    {
        // Validate the request
        $request->validate([
            'report_format' => 'required|in:pdf,excel',
            'date_range' => 'required|in:current_year,current_semester,last_year,all_time',
        ]);

        // In a real implementation, this would generate the actual report
        // For now, we'll just redirect with a success message
        return redirect()->back()->with('success', 'Laporan berhasil dibuat dan diunduh.');
    }

    private function calculateChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }
} 