<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AchievementModel;
use App\Models\CompetitionModel;
use App\Models\AttachmentModel;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AchievementModel::with('competition')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('competition_name	', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(10)->withQueryString();

        $statuses = [
            ['value' => 'upcoming', 'label' => 'Akan Datang'],
            ['value' => 'active', 'label' => 'Aktif'],
            ['value' => 'completed', 'label' => 'Selesai'],
            ['value' => 'cancelled', 'label' => 'Dibatalkan'],
        ];

        $no = 1;

        if ($request->ajax() || $request->has('ajax')) {
            $tableView = view('mahasiswa.achievements.components.tables', compact('achievements'))->render();
            $paginationView = view('mahasiswa.components.tables.pagination', ['data' => $achievements])->render();
            
            return response()->json([
                'success' => true,
                'table' => $tableView,
                'pagination' => $paginationView
            ]);
        }

        return view('mahasiswa.achievements.index', compact('achievements', 'statuses', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $competitions = CompetitionModel::all()->pluck('name', 'id')->toArray();
        $user_id = auth()->id();

        return view('mahasiswa.achievements.components.add-achievement', compact('competitions',  'user_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|string',
            'level' => 'required|string',
            'date' => 'required|date',
            'description' => 'required|string',
            'competition_id' => 'required|exists:competitions,id',
            'attachments' => 'required',
            'attachments.*' => 'mimes:pdf,jpg,jpeg,png|max:2300',
        ]);

        $achievement = new AchievementModel();
        $achievement->user_id = auth()->id();
        $achievement->status = 'pending';
        $achievement->fill($validatedData);
        $achievement->save();

        $achievement_id = $achievement->achievement_id; 

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('attachments', $fileName, 'public'); // Simpan file
                $fileType = $file->getClientOriginalExtension();
                $fileSize = $file->getSize(); // Ukuran dalam byte

                // Simpan detail file ke tabel achievement_files
                AttachmentModel::create([
                    'achievement_id' => $achievement_id,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $fileSize,
                ]);
            }
        }

        return redirect()->route('Mahasiswa.achievements.index')->with('status', 'Prestasi berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $achievement = AchievementModel::where('user_id', auth()->id())
        //     ->with('competition')
        //     ->findOrFail($id);
            
        // return view('mahasiswa.achievements.components.show-achievement', compact('achievement'));
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
        // $achievement = AchievementModel::where('user_id', auth()->id())->findOrFail($id);

        // // Hapus lampiran terkait jika ada
        // if ($achievement->attachments) {
        //     foreach ($achievement->attachments as $attachment) {
        //         Storage::disk('public')->delete($attachment->file_path);
        //         $attachment->delete();
        //     }
        // }

        // $achievement->delete();
        // return redirect()->route('Mahasiswa.achievements.index')->with('status', 'Prestasi berhasil dihapus!');
    }
}
