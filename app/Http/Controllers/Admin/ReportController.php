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
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf as PdfWriter;

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
                ->count();
                
            $achievements = AchievementModel::join('users', 'achievements.user_id', '=', 'users.id')
                ->where('users.program_studi_id', $program->id)
                ->where('achievements.status', 'verified')
                ->count();
                
            $studentsWithAchievements = AchievementModel::join('users', 'achievements.user_id', '=', 'users.id')
                ->where('users.program_studi_id', $program->id)
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
        $totalExports = DB::table('activity_logs')
            ->where('activity_type', 'report_export')
            ->count();
        
        $pdfExports = DB::table('activity_logs')
            ->where('activity_type', 'report_export')
            ->where('details->format', 'pdf')
            ->count();
        
        $excelExports = DB::table('activity_logs')
            ->where('activity_type', 'report_export')
            ->where('details->format', 'excel')
            ->count();
        
        $currentMonthExports = DB::table('activity_logs')
            ->where('activity_type', 'report_export')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        $lastMonthExports = DB::table('activity_logs')
            ->where('activity_type', 'report_export')
            ->whereMonth('created_at', date('m') - 1 > 0 ? date('m') - 1 : 12)
            ->whereYear('created_at', date('m') - 1 > 0 ? date('Y') : date('Y') - 1)
            ->count();
        
        $exportGrowth = $lastMonthExports > 0 
            ? round((($currentMonthExports - $lastMonthExports) / $lastMonthExports) * 100, 1)
            : ($currentMonthExports > 0 ? 100 : 0);
        
        return view('admin.reports.export', compact(
            'totalExports',
            'pdfExports',
            'excelExports',
            'exportGrowth'
        ));
    }

    public function generateReport(Request $request)
    {
        $reportFormat = $request->input('report_format', 'excel');
        $reportType = 'comprehensive';
        $dateRange = 'all_time';

        $userId = auth()->id();
        \DB::table('activity_logs')->insert([
            'activity_type' => 'report_export',
            'user_id' => $userId,
            'details' => json_encode([
                'format' => $reportFormat,
                'date_range' => $dateRange,
                'report_type' => $reportType,
            ]),
            'description' => "Exported comprehensive report in excel format",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $startDate = null;
        $endDate = Carbon::now();
        
        switch ($dateRange) {
            case 'current_year':
                $startDate = Carbon::now()->startOfYear();
                break;
            case 'current_semester':
                $currentPeriod = PeriodModel::where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now())
                    ->first();
                
                if ($currentPeriod) {
                    $startDate = Carbon::parse($currentPeriod->start_date);
                    $endDate = Carbon::parse($currentPeriod->end_date);
                } else {
                    if (Carbon::now()->month <= 6) {
                        $startDate = Carbon::now()->startOfYear();
                    } else {
                        $startDate = Carbon::now()->setMonth(7)->startOfMonth();
                    }
                }
                break;
            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->subYear()->endOfYear();
                break;
            case 'all_time':
                $startDate = null;
                break;
        }

        $query = \App\Models\AchievementModel::where('status', 'verified');
        
        if ($startDate) {
            $query->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
        }
        
        $achievements = $query->with(['user', 'competition'])->get();
        
        $studyPrograms = StudyProgramModel::all();
        $programStats = [];
        foreach ($studyPrograms as $program) {
            $totalStudents = UserModel::where('program_studi_id', $program->id)
                ->count();
                
            $programAchievements = AchievementModel::join('users', 'achievements.user_id', '=', 'users.id')
                ->where('users.program_studi_id', $program->id)
                ->where('achievements.status', 'verified');
                
            if ($startDate) {
                $programAchievements->whereBetween('achievements.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            
            $achievementCount = $programAchievements->count();
                
            $studentsWithAchievements = clone $programAchievements;
            $studentsWithAchievements = $studentsWithAchievements->distinct('achievements.user_id')
                ->count('achievements.user_id');
                
            $participationRate = $totalStudents > 0 
                ? ($studentsWithAchievements / $totalStudents) * 100 
                : 0;
                
            $successRate = $studentsWithAchievements > 0 
                ? ($achievementCount / $studentsWithAchievements) * 100 
                : 0;
                
            $programStats[] = [
                'name' => $program->name,
                'students' => $totalStudents,
                'achievements' => $achievementCount,
                'participation_rate' => round($participationRate, 1),
                'success_rate' => round($successRate, 1)
            ];
        }
        
        $categories = CategoryModel::all();
        $categoryAchievements = [];
        
        foreach ($categories as $category) {
            $competitionIds = CompetitionModel::where('category_id', $category->id)->pluck('id')->toArray();
            
            $achievementCount = 0;
            if (!empty($competitionIds)) {
                $categoryQuery = AchievementModel::where('status', 'verified')
                    ->whereIn('competition_id', $competitionIds);
                
                if ($startDate) {
                    $categoryQuery->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                }
                
                $achievementCount = $categoryQuery->count();
            }
            
            $categoryAchievements[] = [
                'name' => $category->name,
                'count' => $achievementCount
            ];
        }
        
        usort($categoryAchievements, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        $dateStr = Carbon::now()->format('Y-m-d');
        $filename = "achievement-report-{$reportType}-{$dateStr}";
        
        if ($reportFormat === 'pdf') {
            return $this->generateExcelReport($achievements, $programStats, $categoryAchievements, $reportType, $filename, 'pdf');
        }
        return $this->generateExcelReport($achievements, $programStats, $categoryAchievements, $reportType, $filename, 'excel');
    }
    
    private function generateExcelReport($achievements, $programStats, $categoryAchievements, $reportType, $filename, $outputFormat = 'excel')
    {
        $spreadsheet = new Spreadsheet();
        
        $spreadsheet->getProperties()
            ->setCreator('Achievement Management System')
            ->setLastModifiedBy('Achievement Management System')
            ->setTitle('Achievement Report')
            ->setSubject('Achievement Report')
            ->setDescription('Achievement report generated on ' . Carbon::now()->format('Y-m-d H:i:s'))
            ->setKeywords('achievement report')
            ->setCategory('Report');
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Prestasi');
        
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4338CA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        
        $headers = ['ID', 'Judul', 'Mahasiswa', 'NIM', 'Kompetisi', 'Tipe', 'Tingkat', 'Tanggal', 'Status'];
        $sheet->fromArray([$headers], NULL, 'A1');
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
        
        foreach(range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $row = 2;
        foreach ($achievements as $achievement) {
            $sheet->setCellValue('A' . $row, $achievement->id);
            $sheet->setCellValue('B' . $row, $achievement->title);
            $sheet->setCellValue('C' . $row, $achievement->user->name ?? 'Unknown');
            $sheet->setCellValue('D' . $row, $achievement->user->nim ?? '-');
            $sheet->setCellValue('E' . $row, $achievement->competition_name ?? ($achievement->competition->name ?? '-'));
            $sheet->setCellValue('F' . $row, $achievement->type);
            $sheet->setCellValue('G' . $row, $achievement->level);
            $sheet->setCellValue('H' . $row, $achievement->date);
            $sheet->setCellValue('I' . $row, $achievement->status);
            $row++;
        }
        
        $dataRange = 'A2:I' . ($row - 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        for ($i = 2; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':I' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }
        
        if ($reportType === 'summary') {
            $summarySheet = $spreadsheet->createSheet();
            $summarySheet->setTitle('Ringkasan');
            
            $summarySheet->setCellValue('A1', 'RINGKASAN PRESTASI');
            $summarySheet->mergeCells('A1:B1');
            $summarySheet->getStyle('A1:B1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4338CA'],
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            
            $summarySheet->setCellValue('A2', 'Total Prestasi:');
            $summarySheet->setCellValue('B2', $achievements->count());
            
            $summarySheet->setCellValue('A3', 'Tingkat Internasional:');
            $summarySheet->setCellValue('B3', $achievements->where('level', 'Internasional')->count());
            
            $summarySheet->setCellValue('A4', 'Tingkat Nasional:');
            $summarySheet->setCellValue('B4', $achievements->where('level', 'Nasional')->count());
            
            $summarySheet->setCellValue('A5', 'Tingkat Regional:');
            $summarySheet->setCellValue('B5', $achievements->where('level', 'Regional')->count());
            
            $summarySheet->setCellValue('A6', 'Tingkat Provinsi:');
            $summarySheet->setCellValue('B6', $achievements->where('level', 'Provinsi')->count());

            $summarySheet->setCellValue('A7', 'Tingkat Lokal:');
            $summarySheet->setCellValue('B7', $achievements->where('level', 'Lokal')->count());
            
            $summarySheet->getStyle('A2:B7')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            
            $summarySheet->getColumnDimension('A')->setAutoSize(true);
            $summarySheet->getColumnDimension('B')->setAutoSize(true);
        }
        
        $programSheet = $spreadsheet->createSheet();
        $programSheet->setTitle('Program Studi');
        
        $programSheet->setCellValue('A1', 'Program Studi');
        $programSheet->setCellValue('B1', 'Jumlah Mahasiswa');
        $programSheet->setCellValue('C1', 'Prestasi');
        $programSheet->setCellValue('D1', 'Tingkat Partisipasi (%)');
        $programSheet->setCellValue('E1', 'Tingkat Keberhasilan (%)');
        
        $programSheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($programStats as $program) {
            $programSheet->setCellValue('A' . $row, $program['name']);
            $programSheet->setCellValue('B' . $row, $program['students']);
            $programSheet->setCellValue('C' . $row, $program['achievements']);
            $programSheet->setCellValue('D' . $row, $program['participation_rate']);
            $programSheet->setCellValue('E' . $row, $program['success_rate']);
            $row++;
        }
        
        $dataRange = 'A2:E' . ($row - 1);
        $programSheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        for ($i = 2; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $programSheet->getStyle('A' . $i . ':E' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }
        
        foreach(range('A', 'E') as $col) {
            $programSheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $categorySheet = $spreadsheet->createSheet();
        $categorySheet->setTitle('Kategori');
        
        $categorySheet->setCellValue('A1', 'Kategori');
        $categorySheet->setCellValue('B1', 'Jumlah Prestasi');
        
        $categorySheet->getStyle('A1:B1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($categoryAchievements as $category) {
            $categorySheet->setCellValue('A' . $row, $category['name']);
            $categorySheet->setCellValue('B' . $row, $category['count']);
            $row++;
        }
        
        $dataRange = 'A2:B' . ($row - 1);
        $categorySheet->getStyle($dataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        for ($i = 2; $i < $row; $i++) {
            if ($i % 2 == 0) {
                $categorySheet->getStyle('A' . $i . ':B' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }
        
        foreach(range('A', 'B') as $col) {
            $categorySheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // NEW: Add Yearly Trend Sheet
        $trendSheet = $spreadsheet->createSheet();
        $trendSheet->setTitle('Tren Tahunan');
        
        // Prepare yearly data for the last 4 years
        $currentYear = Carbon::now()->year;
        $yearlyData = [];
        for ($i = 0; $i < 4; $i++) {
            $year = $currentYear - $i;
            $yearAchievements = AchievementModel::where('status', 'verified')
                ->whereYear('date', $year)
                ->count();
            $yearParticipants = AchievementModel::where('status', 'verified')
                ->whereYear('date', $year)
                ->distinct('user_id')
                ->count('user_id');
            $successRate = $yearParticipants > 0 ? round($yearAchievements / $yearParticipants, 2) : 0;
            $prevAchievements = AchievementModel::where('status', 'verified')
                ->whereYear('date', $year - 1)
                ->count();
            $change = $this->calculateChange($yearAchievements, $prevAchievements);
            $yearlyData[] = [
                'year' => $year,
                'achievements' => $yearAchievements,
                'participants' => $yearParticipants,
                'success_rate' => $successRate,
                'change' => $change,
            ];
        }
        // Reverse to chronological order (oldest first)
        $yearlyData = array_reverse($yearlyData);
        
        // Headers
        $trendHeaders = ['Tahun', 'Prestasi', 'Partisipan', 'Tingkat Keberhasilan', '% Perubahan'];
        $trendSheet->fromArray([$trendHeaders], NULL, 'A1');
        $trendSheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        
        $row = 2;
        foreach ($yearlyData as $data) {
            $trendSheet->setCellValue('A' . $row, $data['year']);
            $trendSheet->setCellValue('B' . $row, $data['achievements']);
            $trendSheet->setCellValue('C' . $row, $data['participants']);
            $trendSheet->setCellValue('D' . $row, $data['success_rate']);
            $trendSheet->setCellValue('E' . $row, $data['change']);
            $row++;
        }
        $trendDataRange = 'A2:E' . ($row - 1);
        $trendSheet->getStyle($trendDataRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        foreach(range('A', 'E') as $col) {
            $trendSheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // NEW: Add Period Comparison Sheet when at least two periods exist
        $currentPeriod = PeriodModel::orderBy('end_date', 'desc')->first();
        $previousPeriod = PeriodModel::orderBy('end_date', 'desc')->skip(1)->first();
        if ($currentPeriod && $previousPeriod) {
            $periodSheet = $spreadsheet->createSheet();
            $periodSheet->setTitle('Perbandingan');
            
            $periodHeaders = ['Metode', $currentPeriod->name, $previousPeriod->name, 'Growth (%)'];
            $periodSheet->fromArray([$periodHeaders], NULL, 'A1');
            $periodSheet->getStyle('A1:D1')->applyFromArray($headerStyle);
            
            // Calculate metrics
            $metrics = [];
            // Total Achievements
            $currentAch = AchievementModel::where('status', 'verified')->where('period_id', $currentPeriod->id)->count();
            $prevAch = AchievementModel::where('status', 'verified')->where('period_id', $previousPeriod->id)->count();
            $metrics[] = ['Total Prestasi', $currentAch, $prevAch, $this->calculateChange($currentAch, $prevAch)];
            // Participation
            $currentPart = AchievementModel::where('status', 'verified')->where('period_id', $currentPeriod->id)->distinct('user_id')->count('user_id');
            $prevPart = AchievementModel::where('status', 'verified')->where('period_id', $previousPeriod->id)->distinct('user_id')->count('user_id');
            $metrics[] = ['Partisipasi', $currentPart, $prevPart, $this->calculateChange($currentPart, $prevPart)];
            // International Achievements
            $currentInt = AchievementModel::where('status', 'verified')->where('period_id', $currentPeriod->id)->where('level', 'Internasional')->count();
            $prevInt = AchievementModel::where('status', 'verified')->where('period_id', $previousPeriod->id)->where('level', 'Internasional')->count();
            $metrics[] = ['Internasional', $currentInt, $prevInt, $this->calculateChange($currentInt, $prevInt)];
            // National Achievements
            $currentNat = AchievementModel::where('status', 'verified')->where('period_id', $currentPeriod->id)->where('level', 'Nasional')->count();
            $prevNat = AchievementModel::where('status', 'verified')->where('period_id', $previousPeriod->id)->where('level', 'Nasional')->count();
            $metrics[] = ['Nasional', $currentNat, $prevNat, $this->calculateChange($currentNat, $prevNat)];
            
            $periodSheet->fromArray($metrics, NULL, 'A2');
            $periodDataRange = 'A2:D' . (count($metrics) + 1);
            $periodSheet->getStyle($periodDataRange)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            foreach(range('A', 'D') as $col) {
                $periodSheet->getColumnDimension($col)->setAutoSize(true);
            }
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        
        if ($outputFormat === 'pdf') {
            $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
            $writer = new PdfWriter($spreadsheet);
            $writer->writeAllSheets();
            $writer->save($tempFile);
            return response()->download($tempFile, $filename . '.pdf', [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
        }
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        return response()->download($tempFile, $filename . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function calculateChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }
} 