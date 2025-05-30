<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\PeriodModel;
class  CompetitionContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CompetitionModel::with(['addedBy', 'period', 'skills'])
            ->withCount('participants')
            ->orderBy('created_at', 'desc');
            
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }
        
        $competitions = $query->paginate(10)->withQueryString();
        
        $totalCompetitions = CompetitionModel::count();
        $activeCompetitions = CompetitionModel::where('status', 'active')->count();
        $completedCompetitions = CompetitionModel::where('status', 'completed')->count();
        $registeredParticipants = \App\Models\CompetitionParticipantModel::count();
        $categories = \App\Models\CategoryModel::all();
        $skills = SkillModel::all();
        $periods = PeriodModel::all();
        
        $statuses = [
            ['value' => 'upcoming', 'label' => 'Akan Datang'],
            ['value' => 'active', 'label' => 'Aktif'],
            ['value' => 'completed', 'label' => 'Selesai'],
            ['value' => 'cancelled', 'label' => 'Dibatalkan'],
        ];
        
        $levels = [
            ['value' => 'international', 'label' => 'Internasional'],
            ['value' => 'national', 'label' => 'Nasional'],
            ['value' => 'regional', 'label' => 'Regional'],
            ['value' => 'provincial', 'label' => 'Provinsi'],
            ['value' => 'university', 'label' => 'Universitas'],
        ];
        
        if ($request->ajax() || $request->has('ajax')) {
            $tableView = view('admin.competitions.components.tables', compact('competitions'))->render();
            $paginationView = view('admin.components.tables.pagination', ['data' => $competitions])->render();
            
            return response()->json([
                'success' => true,
                'table' => $tableView,
                'pagination' => $paginationView,
                'stats' => [
                    'totalCompetitions' => $totalCompetitions,
                    'activeCompetitions' => $activeCompetitions,
                    'completedCompetitions' => $completedCompetitions,
                    'registeredParticipants' => $registeredParticipants,
                ],
            ]);
        }
        
        return view('Mahasiswa.competitions.index', compact(
            'competitions', 
            'totalCompetitions', 
            'activeCompetitions', 
            'completedCompetitions', 
            'registeredParticipants',
            'statuses',
            'levels',
            'categories',
            'skills',
            'periods'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetitionModel $competition, Request $request)
    {
        $competition->load(['addedBy', 'period']);
        
        return response()->json([
            'success' => true,
            'data' => $competition->append(['requirements_html', 'level_formatted']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
