<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\PeriodModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\SubCompetitionModel;
use App\Models\RecommendationModel;
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
        
        $recommendedCompetitions = 0;
        $recommendations = collect();
        
        if (Auth::check()) {
            $userId = Auth::id();
            
            $recommendations = RecommendationModel::with('competition')
                ->where('user_id', $userId)
                ->where('for_lecturer', true)
                ->whereNotIn('status', ['pending', 'rejected'])
                ->orderBy('match_score', 'desc')
                ->get();
                
            $recommendedCompetitions = $recommendations->count();
            
            \Log::info('Lecturer ID: ' . $userId . ', Found ' . $recommendedCompetitions . ' recommendations');
            if ($recommendedCompetitions > 0) {
                \Log::info('First lecturer recommendation status: ' . $recommendations->first()->status);
            }
        }
        
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
                    'recommendedCompetitions' => $recommendedCompetitions
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
            'periods',
            'recommendations',
            'recommendedCompetitions'
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
        try {
            // Add logging
            \Log::info("Fetching competition with ID: {$id}");
            
            // Check if competition exists
            $exists = CompetitionModel::where('id', $id)->exists();
            if (!$exists) {
                \Log::warning("Competition with ID {$id} not found");
                return response()->json([
                    'success' => false,
                    'message' => 'Kompetisi tidak ditemukan',
                ], 404);
            }
            
            // Get the competition
            $competition = CompetitionModel::findOrFail($id);
            \Log::info("Competition found: " . $competition->name);
            
            // Load relationships safely
            try {
                $period = $competition->period()->first();
                $competition->setRelation('period', $period);
                
                $addedBy = $competition->addedBy()->first();
                $competition->setRelation('addedBy', $addedBy);
                
                \Log::info("Relationships loaded successfully");
            } catch (\Exception $relationError) {
                \Log::error("Error loading relationships: " . $relationError->getMessage());
                // Continue even if relationships fail to load
            }
            
            // Add level_formatted attribute
            $competition = $competition->append(['level_formatted']);
            
            return response()->json([
                'success' => true,
                'competition' => $competition,
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in CompetitionController@show: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data kompetisi',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
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
        $validated = $request->validate([
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

        if ($request->ajax() || $request->wantsJson()) {
            $subCompetitions = $competition->subCompetitions()->with(['category'])->get();
            $tableHtml = view('Dosen.competitions.sub-competitions.table', compact('competition', 'subCompetitions'))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Sub-kompetisi berhasil ditambahkan.',
                'data' => $subCompetition,
                'html' => $tableHtml
            ]);
        }

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
    
    public function updateSubCompetition(Request $request, $competitionId, $subCompetitionId)
    {
        $validated = $request->validate([
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
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);

        if ($subCompetition->competition_id != $competition->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-kompetisi tidak ditemukan dalam kompetisi ini.'
            ], 422);
        }

        $subCompetition->name = $request->name;
        $subCompetition->description = $request->description;
        $subCompetition->category_id = $request->category_id;
        $subCompetition->start_date = $request->start_date;
        $subCompetition->end_date = $request->end_date;
        $subCompetition->registration_start = $request->registration_start;
        $subCompetition->registration_end = $request->registration_end;
        $subCompetition->competition_date = $request->competition_date;
        $subCompetition->registration_link = $request->registration_link;
        $subCompetition->requirements = $request->requirements;
        $subCompetition->status = $request->status ?? $subCompetition->status;
        $subCompetition->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sub-kompetisi berhasil diperbarui.',
                'data' => $subCompetition
            ]);
        }

        return redirect()->route('lecturer.competitions.sub-competitions.index', $competitionId)
            ->with('success', 'Sub-kompetisi berhasil diperbarui.');
    }
    
    public function participants($competition, $sub_competition)
    {
        $competition = CompetitionModel::findOrFail($competition);
        $subCompetition = SubCompetitionModel::findOrFail($sub_competition);
        $participants = $subCompetition->participants;
        $students = UserModel::where(function($q) {
                                $q->where('role', 'MHS')
                                  ->orWhere('level_id', 3);
                             })
                             ->orderBy('name')
                             ->get();
        
        return view('Dosen.competitions.sub-competitions.participants', compact('competition', 'subCompetition', 'participants', 'students'));
    }
    
    public function storeParticipant(Request $request, $competition, $sub_competition)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'team_name' => 'nullable|string|max:255',
            'advisor_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $subCompetition = SubCompetitionModel::findOrFail($sub_competition);
        
        $existingParticipant = $subCompetition->participants()
            ->where('user_id', $request->student_id)
            ->first();
            
        if ($existingParticipant) {
            return redirect()->back()->with('error', 'Mahasiswa sudah terdaftar sebagai peserta.');
        }
        
        $participant = new \App\Models\SubCompetitionParticipantModel();
        $participant->sub_competition_id = $subCompetition->id;
        $participant->user_id = $request->student_id;
        $participant->team_name = $request->team_name;
        $participant->advisor_name = $request->advisor_name;
        $participant->status = $request->status ?? 'registered';
        $participant->save();

        return redirect()->back()->with('success', 'Peserta berhasil ditambahkan.');
    }
    
    public function updateParticipantStatus(Request $request, $competition, $sub_competition, $participant)
    {
        $request->validate([
            'status' => 'required|in:registered,pending',
        ]);

        $participant = \App\Models\SubCompetitionParticipantModel::findOrFail($participant);
        $participant->status = $request->status;
        $participant->save();

        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }
    
    public function skills($competition, $sub_competition)
    {
        try {
            $competition = CompetitionModel::findOrFail($competition);
            $subCompetition = SubCompetitionModel::with('skills')->findOrFail($sub_competition);
            
            if ($subCompetition->competition_id != $competition->id) {
                $correctCompetitionId = $subCompetition->competition_id;
                return redirect()
                    ->route('lecturer.competitions.sub-competitions.skills', [
                        'competition' => $correctCompetitionId,
                        'sub_competition' => $sub_competition
                    ])
                    ->with('info', 'Anda telah dialihkan ke kompetisi yang benar.');
            }
            
            $allSkills = SkillModel::orderBy('category', 'asc')->orderBy('name', 'asc')->get();
            
            return view('Dosen.competitions.sub-competitions.skills.index', compact('competition', 'subCompetition', 'allSkills'));
        } catch (\Exception $e) {
            return redirect()
                ->route('lecturer.competitions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function storeSkill(Request $request, $competition, $sub_competition)
    {
        try {
            $validated = $request->validate([
                'skill_id' => 'required|exists:skills,id',
                'importance_level' => 'required|integer|min:1|max:10',
                'weight_value' => 'nullable|numeric|min:0',
                'criterion_type' => 'nullable|string|in:benefit,cost',
            ]);
            
            $competition = CompetitionModel::findOrFail($competition);
            $subCompetition = SubCompetitionModel::findOrFail($sub_competition);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-kompetisi tidak ditemukan dalam kompetisi ini'
                ], 422);
            }
            
            $exists = $subCompetition->skills()->where('skill_id', $request->skill_id)->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill sudah ditambahkan ke sub-kompetisi ini'
                ], 422);
            }
            
            $skill = SkillModel::findOrFail($request->skill_id);
            
            $subCompetition->skills()->attach($request->skill_id, [
                'importance_level' => $request->importance_level,
                'weight_value' => $request->weight_value ?? 1.0,
                'criterion_type' => $request->criterion_type ?? 'benefit',
                'ahp_priority' => 0.0
            ]);
            
            $subCompetition->load('skills');
            
            $skills = $subCompetition->skills;
            $html = view('Dosen.competitions.sub-competitions.skills.table', compact('skills'))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil ditambahkan',
                'data' => [
                    'skill' => $skill,
                    'importance_level' => $request->importance_level,
                    'weight_value' => $request->weight_value ?? 1.0,
                    'criterion_type' => $request->criterion_type ?? 'benefit',
                ],
                'html' => $html
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan skill: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateSkill(Request $request, $competition, $sub_competition, $skill)
    {
        try {
            $request->validate([
                'importance_level' => 'required|integer|min:1|max:10',
                'weight_value' => 'nullable|numeric|min:0',
                'criterion_type' => 'nullable|string|in:benefit,cost',
            ]);
            
            $competition = CompetitionModel::findOrFail($competition);
            $subCompetition = SubCompetitionModel::findOrFail($sub_competition);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-kompetisi tidak ditemukan dalam kompetisi ini'
                ], 422);
            }
            
            $skillModel = SkillModel::findOrFail($skill);
            
            $subCompetition->skills()->updateExistingPivot($skill, [
                'importance_level' => $request->importance_level,
                'weight_value' => $request->weight_value ?? 1.0,
                'criterion_type' => $request->criterion_type ?? 'benefit',
            ]);
            
            $subCompetition->load('skills');
            
            $skills = $subCompetition->skills;
            $html = view('Dosen.competitions.sub-competitions.skills.table', compact('skills'))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil diperbarui',
                'data' => [
                    'skill' => $skillModel,
                    'importance_level' => $request->importance_level,
                    'weight_value' => $request->weight_value ?? 1.0,
                    'criterion_type' => $request->criterion_type ?? 'benefit',
                ],
                'html' => $html
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui skill: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroySkill($competition, $sub_competition, $skill)
    {
        try {
            $competition = CompetitionModel::findOrFail($competition);
            $subCompetition = SubCompetitionModel::findOrFail($sub_competition);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-kompetisi tidak ditemukan dalam kompetisi ini'
                ], 422);
            }
            
            $exists = $subCompetition->skills()->where('skill_id', $skill)->exists();
            
            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill tidak ditemukan pada sub-kompetisi ini'
                ], 404);
            }
            
            $subCompetition->skills()->detach($skill);
            
            $subCompetition->load('skills');
            
            $skills = $subCompetition->skills;
            $html = view('Dosen.competitions.sub-competitions.skills.table', compact('skills'))->render();
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil dihapus dari sub-kompetisi',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus skill: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function applySubCompetition(Request $request, $competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::where('id', $competitionId)
            ->firstOrFail();
        
        $subCompetition = SubCompetitionModel::where('id', $subCompetitionId)
            ->where('competition_id', $competitionId)
            ->firstOrFail();
            
        $user = Auth::user();
        $exists = $subCompetition->participants()
            ->where('user_id', $user->id)
            ->exists();
            
        if ($exists) {
            return redirect()
                ->route('lecturer.competitions.sub-competitions.index', $competitionId)
                ->with('error', 'Anda sudah terdaftar pada kategori kompetisi ini.');
        }
        
        $now = \Carbon\Carbon::now();
        if ($subCompetition->registration_start && $subCompetition->registration_end) {
            $startDate = \Carbon\Carbon::parse($subCompetition->registration_start);
            $endDate = \Carbon\Carbon::parse($subCompetition->registration_end);
            
            if ($now->lt($startDate)) {
                return redirect()
                    ->route('lecturer.competitions.sub-competitions.index', $competitionId)
                    ->with('error', 'Pendaftaran belum dibuka. Pendaftaran dimulai pada ' . $startDate->format('d M Y') . '.');
            }
            
            if ($now->gt($endDate)) {
                // Show toast/alert for registration period ended
                return view('Dosen.competitions.sub-competitions.registration_closed', compact(
                    'competition',
                    'subCompetition',
                    'endDate'
                ));
            }
        } else {
            return redirect()
                ->route('lecturer.competitions.sub-competitions.index', $competitionId)
                ->with('error', 'Periode pendaftaran belum ditentukan. Silahkan hubungi penyelenggara.');
        }
        
        if (request()->isMethod('post')) {
            $validated = request()->validate([
                'team_name' => 'required|string|max:255',
                'notes' => 'nullable|string',
                'terms' => 'required',
                'team_members' => 'required|array|min:1',
                'team_members.0' => 'required|exists:users,id',
                'team_members.1' => 'nullable|exists:users,id|different:team_members.0',
                'team_members.2' => 'nullable|exists:users,id|different:team_members.0|different:team_members.1',
            ], [
                'team_members.required' => 'Minimal satu mahasiswa harus dipilih sebagai ketua tim.',
                'team_members.0.required' => 'Ketua tim harus dipilih.',
                'team_members.1.different' => 'Anggota tim harus berbeda dengan ketua tim.',
                'team_members.2.different' => 'Anggota tim harus berbeda dengan anggota tim lainnya.',
            ]);
            
            $teamMembers = array_filter($validated['team_members'] ?? [], function($value) {
                return !empty($value);
            });
            
            if (count($teamMembers) !== count(array_unique($teamMembers))) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['team_members' => 'Setiap anggota tim harus berbeda. Anda tidak dapat memilih mahasiswa yang sama lebih dari sekali.']);
            }
            
            $participant = new \App\Models\SubCompetitionParticipantModel();
            $participant->sub_competition_id = $subCompetition->id;
            $participant->user_id = $teamMembers[0]; // First student is the team leader
            $participant->team_name = $validated['team_name'];
            $participant->mentor_id = $user->id; // Current lecturer is automatically the mentor
            $participant->advisor_name = $user->name;
            $participant->status = 'registered'; // Auto-approved
            $participant->status_mentor = 'accept'; // Auto-approved by mentor (lecturer)
            
            if (!empty($teamMembers)) {
                $processedTeamMembers = [];
                $isFirst = true;
                
                foreach ($teamMembers as $memberId) {
                    $memberUser = UserModel::find($memberId);
                    if ($memberUser) {
                        $processedTeamMembers[] = [
                            'id' => $memberId,
                            'name' => $memberUser->name,
                            'nim' => $memberUser->nim ?? '',
                            'email' => $memberUser->email,
                            'role' => $isFirst ? 'ketua' : 'anggota'
                        ];
                        $isFirst = false;
                    }
                }
                
                if (!empty($processedTeamMembers)) {
                    $participant->team_members = json_encode($processedTeamMembers);
                }
            }
            
            $participant->save();
            
            return redirect()
                ->route('lecturer.competitions.sub-competitions.index', $competitionId)
                ->with('success', 'Pendaftaran berhasil! Tim mahasiswa telah terdaftar untuk kompetisi ini dengan Anda sebagai dosen pembimbing.');
        }
        
        $students = UserModel::where(function($q) {
                                $q->where('role', 'MHS')
                                  ->orWhere('level_id', 3);
                             })
                             ->orderBy('name')
                             ->get();
        
        return view('Dosen.competitions.sub-competitions.apply', compact(
            'competition', 
            'subCompetition',
            'students'
        ));
    }
} 