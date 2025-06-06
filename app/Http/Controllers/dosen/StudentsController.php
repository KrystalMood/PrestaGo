<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\SubCompetitionParticipantModel;
use App\Models\SubCompetitionModel;
use App\Models\CompetitionModel;
use App\Models\CompetitionParticipantModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();

        
        $subCompetitionsStudents = SubCompetitionParticipantModel::where('mentor_id', $user->id)
            ->with(['user.programStudi', 'subCompetition'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id ?? '-',
                    'name' => $item->user->name ?? '-',
                    'nim' => $item->user->nim ?? '-',
                    'program_studi' => $item->user->programStudi->name ?? '-', // ganti 'nama' jadi 'name'
                    'competition' => $item->subCompetition->name ?? '-',         // ganti 'nama' jadi 'name'
                    'jenis' => 'Sub Kompetisi',
                    'status' => $item->status ?? '-',
                ];
            });

        $competitionsStudents = CompetitionParticipantModel::where('mentor_id', $user->id)
            ->with(['user.programStudi', 'competition'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id ?? '-',
                    'name' => $item->user->name ?? '-',
                    'nim' => $item->user->nim ?? '-',
                    'program_studi' => $item->user->programStudi->name ?? '-', // ganti 'nama' jadi 'name'
                    'competition' => $item->competition->name ?? '-',            // ganti 'nama' jadi 'name'
                    'jenis' => 'Kompetisi',
                    'status' => $item->status ?? '-',
                ];
            });

        // Gabungkan kedua collection
        $students = $subCompetitionsStudents->concat($competitionsStudents);
        $totalStudents = $students->count();
        $rejectedStudents = $students->where('status', 'rejected')->count();
        $regristedStudents = $students->where('status', 'registered')->count();
        $onGoingStudents = $students->where('status', 'on going')->count();

        return view('Dosen.students.index', compact(
            'students',
            'totalStudents',
            'rejectedStudents',
            'regristedStudents',
            'onGoingStudents'
        ));
    }

    public function show(Request $request, $id)
    {
        $jenis = $request->query('jenis'); // Ambil dari query string

        if ($jenis == 'Kompetisi') {
            // Ambil dari CompetitionParticipantModel
            $student = CompetitionParticipantModel::with(['user.programStudi', 'competition'])
                ->where('id', $id)
                ->first();
            $teamMembersData = []; // Kompetisi biasanya tidak punya team_members
        } else {
            // Ambil dari SubCompetitionParticipantModel
            $student = SubCompetitionParticipantModel::with(['user.programStudi', 'subCompetition.competition', 'subCompetition.category'])
                ->where('id', $id)
                ->first();

            // Ambil data team_members jika ada
            $teamMemberIds = is_array($student->team_members) ? $student->team_members : json_decode($student->team_members, true);
            $teamMembersData = [];
            if ($teamMemberIds && is_array($teamMemberIds)) {
                $teamMembers = \App\Models\UserModel::whereIn('id', $teamMemberIds)->get(['id', 'name', 'nim']);
                $teamMembersData = $teamMembers->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'nim' => $user->nim,
                    ];
                })->toArray();
            }
        }

        // Lakukan pengecekan dan return response sesuai kebutuhan (misal JSON)
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Mapping data sesuai kebutuhan frontend
        $data = [
            'id' => $student->id,
            'nama' => $student->user->name ?? '-',
            'nim' => $student->user->nim ?? '-',
            'level' => $jenis == 'Kompetisi'
                ? ($student->competition->level ?? '-')
                : ($student->subCompetition->competition->level ?? '-'), // <-- akses level dari competition
            'kompetisi' => $jenis == 'Kompetisi'
                ? ($student->competition->name ?? '-')
                : ($student->subCompetition->name ?? '-'),
            'status' => $student->status ?? '-',
            'team_members' => $teamMembersData,
            'advisor' => $jenis == 'Sub Kompetisi'
                ? ($student->advisor_name ?? '-')
                : '-',
            'notes' => $jenis == 'Kompetisi'
                ? ($student->notes ?? '-')
                : '-',
            ];

        return response()->json(['success' => true, 'student' => $data]);
    }

}
