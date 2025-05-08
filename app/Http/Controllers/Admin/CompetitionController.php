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
        $registeredParticipants = 0; // This should be calculated from your participants table
        
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
        
        return view('admin.competitions.index', compact(
            'competitions', 
            'totalCompetitions', 
            'activeCompetitions', 
            'completedCompetitions', 
            'registeredParticipants',
            'statuses',
            'levels'
        ));
    }

    public function create()
    {
        $periods = PeriodModel::orderBy('name')->get();
        $skills = SkillModel::orderBy('name')->get();
        
        return view('admin.competitions.create', compact('periods', 'skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'organizer' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'competition_date' => 'required|date|after_or_equal:registration_start',
            'registration_link' => 'nullable|url|max:255',
            'requirements' => 'required|string',
            'status' => 'required|string|in:upcoming,active,completed,cancelled',
            'period_id' => 'required|exists:periods,id',
            'skills' => 'nullable|array',
            'skills.*.skill_id' => 'exists:skills,id',
            'skills.*.importance_level' => 'integer|min:1|max:5',
        ]);
        
        $validated['added_by'] = Auth::id();
        $validated['verified'] = true;
        
        $competition = CompetitionModel::create($validated);
        
        if (isset($validated['skills']) && !empty($validated['skills'])) {
            foreach ($validated['skills'] as $skill) {
                $competition->skills()->attach($skill['skill_id'], [
                    'importance_level' => $skill['importance_level'] ?? 3
                ]);
            }
        }
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil dibuat!');
    }

    public function show(CompetitionModel $competition)
    {
        $competition->load(['addedBy', 'period', 'skills', 'participants', 'participants.user']);
        
        return view('admin.competitions.show', compact('competition'));
    }

    public function edit(CompetitionModel $competition)
    {
        $periods = PeriodModel::orderBy('name')->get();
        $skills = SkillModel::orderBy('name')->get();
        $competition->load(['skills']);
        
        return view('admin.competitions.edit', compact('competition', 'periods', 'skills'));
    }

    public function update(Request $request, CompetitionModel $competition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'organizer' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'competition_date' => 'required|date|after_or_equal:registration_start',
            'registration_link' => 'nullable|url|max:255',
            'requirements' => 'required|string',
            'status' => 'required|string|in:upcoming,active,completed,cancelled',
            'period_id' => 'required|exists:periods,id',
            'skills' => 'nullable|array',
            'skills.*.skill_id' => 'exists:skills,id',
            'skills.*.importance_level' => 'integer|min:1|max:5',
        ]);
        
        $competition->update($validated);
        
        if (isset($validated['skills'])) {
            $competition->skills()->detach();
            
            foreach ($validated['skills'] as $skill) {
                $competition->skills()->attach($skill['skill_id'], [
                    'importance_level' => $skill['importance_level'] ?? 3
                ]);
            }
        }
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'Kompetisi berhasil diperbarui!');
    }

    public function destroy(CompetitionModel $competition)
    {
        if ($competition->participants()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kompetisi yang sudah memiliki peserta.');
        }
        
        $competition->skills()->detach();
        
        $competition->delete();
        
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
} 