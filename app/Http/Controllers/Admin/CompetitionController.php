<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\PeriodModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompetitionController extends Controller
{ 
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
            'level' => 'required|string|in:international,national,regional,provincial,university',
            'type' => 'required|string|in:individual,team,both',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'required|date',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
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
        $competition->load(['addedBy', 'period']);
        
        return response()->json([
            'success' => true,
            'data' => $competition->append(['requirements_html', 'level_formatted']),
        ]);
    }

    public function update(Request $request, CompetitionModel $competition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organizer' => 'required|string|max:255',
            'level' => 'required|string|in:international,national,regional,provincial,university',
            'type' => 'required|string|in:individual,team,both',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'required|date',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
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
        
        $competition->skills()->detach();
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
        
        $status = $competition->verified ? 'diverifikasi' : 'belum diverifikasi';
        
        return back()->with('success', "Kompetisi telah {$status}!");
    }
    
    public function participants(CompetitionModel $competition)
    {
        $competition->load(['participants', 'participants.user']);
        
        $students = UserModel::where('role', 'MHS')
            ->whereNotIn('id', $competition->participants->pluck('user_id')) // Exclude already participating students
            ->orderBy('name')
            ->get();
        
        return view('admin.competitions.participants', compact('competition', 'students'));
    }

    public function addParticipant(Request $request, CompetitionModel $competition)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'team_name' => 'nullable|string|max:255',
            'status' => 'required|in:registered,pending',
        ]);
        
        $exists = $competition->participants()->where('user_id', $validated['student_id'])->exists();
        
        if ($exists) {
            return back()->with('error', 'Mahasiswa ini sudah terdaftar pada kompetisi ini.');
        }
        
        $competition->participants()->create([
            'user_id' => $validated['student_id'],
            'team_name' => $validated['team_name'],
            'status' => $validated['status'],
        ]);
        
        return back()->with('success', 'Peserta berhasil ditambahkan.');
    }
    
    public function removeParticipant(CompetitionModel $competition, $participant)
    {
        $competition->participants()->findOrFail($participant)->delete();
        
        return back()->with('success', 'Peserta berhasil dihapus.');
    }
} 