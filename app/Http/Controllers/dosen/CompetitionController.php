<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\PeriodModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\SubCompetitionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompetitionController extends Controller
{ 
    public function index(Request $request)
    {
        // Use query builder without eager loading initially
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
        
        // Get the paginated results
        $competitions = $query->paginate(10)->withQueryString();
        
        // Manually load relationships for each competition
        foreach ($competitions as $competition) {
            $competition->setRelation('addedBy', $competition->addedBy()->first());
            $competition->setRelation('period', $competition->period()->first());
            $competition->setRelation('skills', $competition->skills()->get());
            $competition->setRelation('subCompetitions', $competition->subCompetitions()->get());
            
            // Load participants for each sub-competition
            foreach ($competition->subCompetitions as $subCompetition) {
                $subCompetition->setRelation('participants', $subCompetition->participants()->get());
            }
        }
        
        $totalCompetitions = CompetitionModel::count();
        $activeCompetitions = CompetitionModel::where('status', 'active')->count();
        $completedCompetitions = CompetitionModel::where('status', 'completed')->count();
        $registeredParticipants = \App\Models\SubCompetitionParticipantModel::count();
        $categories = CategoryModel::all();
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
            $tableView = view('Dosen.competitions.components.tables', compact('competitions'))->render();
            $paginationView = view('Dosen.components.tables.pagination', ['data' => $competitions])->render();
            
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
        
        return view('Dosen.competitions.index', compact(
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
        
        return redirect()->route('lecturer.competitions.index')
            ->with('success', 'Kompetisi berhasil dibuat!');
    }

    public function show($id)
    {
        $competition = CompetitionModel::findOrFail($id);
        
        // Manually load relationships to avoid the addEagerConstraints error
        $competition->setRelation('period', $competition->period()->first());
        $competition->setRelation('addedBy', $competition->addedBy()->first());
        
        return response()->json([
            'success' => true,
            'competition' => $competition->append(['level_formatted']),
        ]);
    }
    
    public function subCompetitions($competitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetitions = $competition->subCompetitions()->with(['category', 'skills'])->get();
        $categories = CategoryModel::all();
        
        return view('Dosen.competitions.sub-competitions.index', compact('competition', 'subCompetitions', 'categories'));
    }
    
    public function createSubCompetition($competitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $categories = CategoryModel::all();
        
        return view('Dosen.competitions.sub-competitions.create', compact('competition', 'categories'));
    }
    
    public function storeSubCompetition(Request $request, $competitionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'nullable|date',
            'registration_link' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string|max:20',
        ]);

        $competition = CompetitionModel::findOrFail($competitionId);
        
        $subCompetition = new SubCompetitionModel();
        $subCompetition->name = $request->name;
        $subCompetition->description = $request->description;
        $subCompetition->competition_id = $competition->id;
        $subCompetition->category_id = $request->category_id;
        $subCompetition->start_date = $request->start_date;
        $subCompetition->end_date = $request->end_date;
        $subCompetition->registration_start = $request->registration_start;
        $subCompetition->registration_end = $request->registration_end;
        $subCompetition->competition_date = $request->competition_date;
        $subCompetition->registration_link = $request->registration_link;
        $subCompetition->requirements = $request->requirements;
        $subCompetition->status = $request->status ?? 'upcoming';
        $subCompetition->save();

        return redirect()->route('lecturer.competitions.sub-competitions.index', $competitionId)
            ->with('success', 'Sub-kompetisi berhasil dibuat!');
    }
    
    public function showSubCompetition($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::with(['category', 'skills'])->findOrFail($subCompetitionId);
        
        return response()->json([
            'success' => true,
            'data' => $subCompetition
        ]);
    }
} 