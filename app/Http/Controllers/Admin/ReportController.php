<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function achievements()
    {
        return view('admin.reports.achievements');
    }

    public function programs()
    {
        return view('admin.reports.programs');
    }

    public function trends()
    {
        return view('admin.reports.trends');
    }

    public function periods()
    {
        $periodData = [
            'period1' => [
                'name' => '2024/2025 - Semester 1',
                'total_achievements' => 156,
                'participation' => 348,
                'international' => 28,
                'national' => 75
            ],
            'period2' => [
                'name' => '2023/2024 - Semester 2',
                'total_achievements' => 134,
                'participation' => 302,
                'international' => 22,
                'national' => 65
            ],
            'changes' => [
                'total_achievements' => '+16.4%',
                'participation' => '+15.2%',
                'international' => '+27.3%',
                'national' => '+15.4%'
            ]
        ];
        
        return view('admin.reports.periods');
    }

    public function export()
    {
        return view('admin.reports.export');
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'report_format' => 'required|in:pdf,excel,ppt,word,html',
            'date_range' => 'required',
            'content' => 'array'
        ]);

        return back()->with('success', 'Report generated successfully. Download started.');
    }
} 