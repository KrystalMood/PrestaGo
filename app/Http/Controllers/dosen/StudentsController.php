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

}
