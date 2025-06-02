<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;
use App\Models\CategoryModel;
use App\Models\SubCompetitionModel;
use App\Models\SubCompetitionParticipantModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel;

class CompetitionController extends Controller
{
    private function updateCompetitionStatuses()
    {
        $today = Carbon::today();
        $updatedCount = 0;
        
        $competitions = CompetitionModel::all();
        
        foreach ($competitions as $competition) {
            $startDate = $competition->start_date;
            $endDate = $competition->end_date;
            $currentStatus = $competition->status;
            $newStatus = null;
            
            if ($startDate && $endDate) {
                if ($today->lt($startDate) && $currentStatus !== 'upcoming') {
                    $newStatus = 'upcoming';
                } elseif ($today->gte($startDate) && $today->lte($endDate) && $currentStatus !== 'active') {
                    $newStatus = 'active';
                } elseif ($today->gt($endDate) && $currentStatus !== 'completed') {
                    $newStatus = 'completed';
                }
                
                if ($newStatus) {
                    $competition->status = $newStatus;
                    $competition->save();
                    $updatedCount++;
                }
            }
        }
        
        $subCompetitions = SubCompetitionModel::all();
        
        foreach ($subCompetitions as $subCompetition) {
            $startDate = $subCompetition->start_date;
            $endDate = $subCompetition->end_date;
            $currentStatus = $subCompetition->status;
            $newStatus = null;
            
            if ($startDate && $endDate) {
                if ($today->lt($startDate) && $currentStatus !== 'upcoming') {
                    $newStatus = 'upcoming';
                } elseif ($today->gte($startDate) && $today->lte($endDate) && $currentStatus !== 'active') {
                    $newStatus = 'active';
                } elseif ($today->gt($endDate) && $currentStatus !== 'completed') {
                    $newStatus = 'completed';
                }
                
                if ($newStatus) {
                    $subCompetition->status = $newStatus;
                    $subCompetition->save();
                    $updatedCount++;
                }
            }
        }
        
        return $updatedCount;
    }
    public function index(Request $request)
    {
        $this->updateCompetitionStatuses();
        
        $query = CompetitionModel::with(['period'])
            ->where('verified', true)
            ->where('status', '!=', 'completed');
            
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('organizer', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('category') && $request->category) {
            $competitionIds = SubCompetitionModel::where('category_id', $request->category)
                ->distinct()
                ->pluck('competition_id')
                ->toArray();
                
            $query->whereIn('id', $competitionIds);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('sort') && $request->sort) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'deadline_asc':
                    $query->orderBy('registration_end', 'asc');
                    break;
                case 'deadline_desc':
                    $query->orderBy('registration_end', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $competitions = $query->paginate(10)->withQueryString();
        
        $totalCompetitions = CompetitionModel::where('verified', true)->count();
        $newCompetitions = CompetitionModel::where('verified', true)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
        $activeCompetitions = CompetitionModel::where('verified', true)
            ->where('status', 'active')
            ->count();
            
        $subCompetitionCategories = SubCompetitionModel::select('category_id')
            ->distinct()
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->map(function($item) {
                return $item->category;
            })
            ->filter()
            ->unique('id');
        
        if ($request->ajax()) {
            $table = view('student.competitions.components.tables', compact('competitions'))->render();
            $pagination = view('student.competitions.components.pagination', ['data' => $competitions])->render();
            
            return response()->json([
                'success' => true,
                'table' => $table,
                'pagination' => $pagination,
                'stats' => [
                    'totalCompetitions' => $totalCompetitions,
                    'newCompetitions' => $newCompetitions,
                    'activeCompetitions' => $activeCompetitions
                ]
            ]);
        }
        
        return view('student.competitions.index', compact(
            'competitions',
            'totalCompetitions',
            'newCompetitions',
            'activeCompetitions',
            'subCompetitionCategories'
        ));
    }

    public function show($id)
    {
        $this->updateCompetitionStatuses();
        
        $competition = CompetitionModel::with(['skills', 'period', 'subCompetitions', 'subCompetitions.category'])
            ->where('id', $id)
            ->where('verified', true)
            ->firstOrFail();
            
        if ($competition->subCompetitions) {
            $competition->setRelation(
                'subCompetitions', 
                $competition->subCompetitions->filter(function($subCompetition) {
                    return $subCompetition->status != 'completed';
                })
            );
        }
            
        $isParticipating = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isParticipating = $competition->participants()->where('user_id', $user->id)->exists();
        }
        
        return view('student.competitions.show', compact('competition', 'isParticipating'));
    }
    
    public function showSubCompetition($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::where('id', $competitionId)
            ->where('verified', true)
            ->firstOrFail();
        
        $subCompetition = SubCompetitionModel::with(['competition', 'category', 'skills'])
            ->where('id', $subCompetitionId)
            ->where('competition_id', $competitionId)
            ->firstOrFail();
            
        if ($subCompetition->status == 'completed') {
            return redirect()
                ->route('student.competitions.show', $competitionId)
                ->with('error', 'Kategori lomba ini telah selesai dan tidak lagi tersedia.');
        }
            
        $isParticipating = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isParticipating = $subCompetition->participants()
                ->where('user_id', $user->id)
                ->exists();
        }
        
        return view('student.competitions.sub.show', compact(
            'competition', 
            'subCompetition', 
            'isParticipating'
        ));
    }
    
    public function applySubCompetition($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::where('id', $competitionId)
            ->where('verified', true)
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
                ->route('student.competitions.sub.show', [$competitionId, $subCompetitionId])
                ->with('error', 'Anda sudah terdaftar pada kategori kompetisi ini.');
        }
        
        $now = Carbon::now();
        if ($subCompetition->registration_start && $subCompetition->registration_end) {
            $startDate = Carbon::parse($subCompetition->registration_start);
            $endDate = Carbon::parse($subCompetition->registration_end);
            
            if ($now->lt($startDate)) {
                return redirect()
                    ->route('student.competitions.sub.show', [$competitionId, $subCompetitionId])
                    ->with('error', 'Pendaftaran belum dibuka. Pendaftaran dimulai pada ' . $startDate->format('d M Y') . '.');
            }
            
            if ($now->gt($endDate)) {
                return redirect()
                    ->route('student.competitions.sub.show', [$competitionId, $subCompetitionId])
                    ->with('error', 'Pendaftaran sudah ditutup pada ' . $endDate->format('d M Y') . '.');
            }
        } else {
            return redirect()
                ->route('student.competitions.sub.show', [$competitionId, $subCompetitionId])
                ->with('error', 'Periode pendaftaran belum ditentukan. Silahkan hubungi penyelenggara.');
        }
        
        if (request()->isMethod('post')) {
            $validated = request()->validate([
                'team_name' => 'required|string|max:255',
                'mentor_id' => 'required|exists:users,id',
                'notes' => 'nullable|string',
                'terms' => 'required',
                'team_members' => 'nullable|array',
                'team_members.*' => 'nullable|exists:users,id|different:' . $user->id,
            ], [
                'team_members.*.different' => 'Anda tidak dapat memilih diri sendiri sebagai anggota tim.',
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
            
            $participant = new SubCompetitionParticipantModel();
            $participant->sub_competition_id = $subCompetition->id;
            $participant->user_id = $user->id;
            $participant->team_name = $validated['team_name'];
            $participant->mentor_id = $validated['mentor_id'];
            
            $lecturer = UserModel::find($validated['mentor_id']);
            if ($lecturer) {
                $participant->advisor_name = $lecturer->name;
            }
            
            $participant->status = 'pending';
            
            if (!empty($teamMembers)) {
                $processedTeamMembers = [];
                
                foreach ($teamMembers as $memberId) {
                    $memberUser = UserModel::find($memberId);
                    if ($memberUser) {
                        $processedTeamMembers[] = [
                            'id' => $memberId,
                            'name' => $memberUser->name,
                            'nim' => $memberUser->nim ?? '',
                            'email' => $memberUser->email
                        ];
                    }
                }
                
                if (!empty($processedTeamMembers)) {
                    $participant->team_members = json_encode($processedTeamMembers);
                }
            }
            
            $participant->save();
            
            return redirect()
                ->route('student.competitions.sub.show', [$competitionId, $subCompetitionId])
                ->with('success', 'Pendaftaran berhasil! Status Anda saat ini: Menunggu verifikasi.');
        }
        
        $lecturers = UserModel::where('level_id', 2)->get();
        
        $students = UserModel::where('level_id', 3)
                            ->where('id', '!=', $user->id)
                            ->orderBy('name')
                            ->get();
        
        return view('student.competitions.sub.apply', compact(
            'competition', 
            'subCompetition',
            'lecturers',
            'students'
        ));
    }
} 