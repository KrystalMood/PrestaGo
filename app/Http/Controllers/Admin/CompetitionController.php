<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\PeriodModel;
use App\Models\UserModel;
use App\Models\SubCompetitionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompetitionController extends Controller
{ 
    public function index(Request $request)
    {
        $query = CompetitionModel::orderBy('created_at', 'desc');
            
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
        
        foreach ($competitions as $competition) {
            $competition->setRelation('addedBy', $competition->addedBy()->first());
            $competition->setRelation('period', $competition->period()->first());
            $competition->setRelation('skills', $competition->skills()->get());
            $competition->setRelation('subCompetitions', $competition->subCompetitions()->get());
            
            foreach ($competition->subCompetitions as $subCompetition) {
                $subCompetition->setRelation('participants', $subCompetition->participants()->get());
            }
        }
        
        $totalCompetitions = CompetitionModel::count();
        $activeCompetitions = CompetitionModel::where('status', 'active')->count();
        $completedCompetitions = CompetitionModel::where('status', 'completed')->count();
        $registeredParticipants = \App\Models\SubCompetitionParticipantModel::count();
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
            ['value' => 'internal', 'label' => 'Internal'],
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
        
        return view('admin.competitions.index', compact(
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

    public function create()
    {
        $skills = SkillModel::all();
        $periods = PeriodModel::all();
        
        return view('admin.competitions.create', compact('skills', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'level' => 'required|string|in:international,national,regional,provincial,university,internal',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string|in:upcoming,active,completed,cancelled',
            'period_id' => 'required|exists:periods,id',
        ]);
        
        $validated['added_by'] = Auth::id();
        $validated['verified'] = true;
        
        $competition = CompetitionModel::create($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kompetisi berhasil ditambahkan.',
                'data' => $competition,
            ]);
        }
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil dibuat!');
    }

    public function show(CompetitionModel $competition, Request $request)
    {
        $competition->setRelation('addedBy', $competition->addedBy()->first());
        $competition->setRelation('period', $competition->period()->first());
        
        return response()->json([
            'success' => true,
            'data' => $competition->append(['level_formatted']),
        ]);
    }

    public function update(Request $request, CompetitionModel $competition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'level' => 'required|string|in:international,national,regional,provincial,university,internal',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|string|in:upcoming,active,completed,cancelled',
            'period_id' => 'required|exists:periods,id',
        ]);
        
        $competition->update($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kompetisi berhasil diperbarui.',
                'data' => $competition,
            ]);
        }
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil diperbarui!');
    }

    public function destroy(CompetitionModel $competition, Request $request)
    {
        if ($competition->participants()->count() > 0) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus kompetisi yang sudah memiliki peserta.',
                ], 400);
            }
            return back()->with('error', 'Tidak dapat menghapus kompetisi yang sudah memiliki peserta.');
        }
        
        $competition->subCompetitions()->delete();
        $competition->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Kompetisi berhasil dihapus.',
            ]);
        }
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil dihapus!');
    }

    public function toggleVerification(CompetitionModel $competition)
    {
        $competition->verified = !$competition->verified;
        $competition->save();
        
        return back()->with('success', 'Status verifikasi kompetisi telah diubah!');
    }
    
    public function getSubCompetitions($competition)
    {
        try {
            \Log::info('Attempting to get sub-competitions for competition ID: ' . $competition);
            \Log::info('Executing SubCompetitionModel query for competition ID: ' . $competition);
            $subCompetitions = SubCompetitionModel::where('competition_id', $competition)
                ->orderBy('name')
                ->get(['id', 'name']);
                
            return response()->json($subCompetitions);
        } catch (\Exception $e) {
            \Log::error('Error getting sub-competitions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch sub-competitions', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getAllSubCompetitions()
    {
        try {
            $subCompetitions = SubCompetitionModel::with('competition')
                ->orderBy('name')
                ->get()
                ->map(function($subCompetition) {
                    return [
                        'id' => $subCompetition->id,
                        'name' => $subCompetition->name,
                        'competition_name' => $subCompetition->competition->name ?? 'Unknown Competition',
                    ];
                });
                
            return response()->json($subCompetitions);
        } catch (\Exception $e) {
            \Log::error('Error getting all sub-competitions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch all sub-competitions', 'message' => $e->getMessage()], 500);
        }
    }
} 