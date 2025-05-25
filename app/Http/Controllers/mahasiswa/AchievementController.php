<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AchievementModel;
use App\Models\CompetitionModel;
use App\Models\AttachmentModel;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    // Function to display a listing of the achievements
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
            $tableView = view('Mahasiswa.achievements.components.tables', compact('achievements'))->render();
            $paginationView = view('Mahasiswa.components.tables.pagination', ['data' => $achievements])->render();

            return response()->json([
                'success' => true,
                'table' => $tableView,
                'pagination' => $paginationView
            ]);
        }

        return view('Mahasiswa.achievements.index', compact('achievements', 'statuses', 'no'));
    }

    // Function to show the form for creating a new achievement
    public function create()
    {
        $competitions = CompetitionModel::all()->pluck('name', 'id')->toArray();
        $user_id = auth()->id();

        return view('Mahasiswa.achievements.components.add-achievement', compact('competitions',  'user_id'));
    }

    // Function to store a newly created achievement in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|string',
            'level' => 'required|string',
            'date' => 'required|date',
            'description' => 'required|string',
            'competition_id' => 'nullable|exists:competitions,id',
            'attachments' => 'required',
            'attachments.*' => 'mimes:pdf,jpg,jpeg,png|max:2300',
        ]);

        try {
            $achievement = new AchievementModel();
            $achievement->user_id = auth()->id();
            $achievement->status = 'pending';
            $achievement->fill($validatedData);

            if (!$achievement->save()) {
                throw new \Exception('Gagal menyimpan achievement.');
            }

            $achievement_id = $achievement->id;

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('attachments', $fileName, 'public');
                    $fileType = strtolower($file->getClientOriginalExtension());
                    $fileSize = $file->getSize();

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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan prestasi: ' . $e->getMessage());
        }
    }

    // Function to display the specified achievement
    public function show(string $id)
    {
        $achievement = AchievementModel::where('user_id', auth()->id())
            ->with(['competition', 'attachments'])
            ->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            $achievement->attachments->each(function ($attachment) {
                $attachment->url = asset('storage/' . $attachment->file_path);
                $attachment->filename = $attachment->file_name;
                $attachment->mime_type = $this->getMimeType($attachment->file_type);
            });
            
            return response()->json([
                'success' => true,
                'achievement' => $achievement
            ]);
        }

        return view('Mahasiswa.achievements.components.show-achievement', compact('achievement'));
    }

    // Function to show the form for editing the specified achievement
    public function edit(string $id)
    {
        $competitions = CompetitionModel::all();

        $achievement = AchievementModel::where('user_id', auth()->id())
            ->with(['competition', 'attachments'])
            ->findOrFail($id);
            
        if (request()->ajax() || request()->wantsJson()) {
            $achievement->attachments->each(function ($attachment) {
                $attachment->url = asset('storage/' . $attachment->file_path);
                $attachment->filename = $attachment->file_name;
                $attachment->mime_type = $this->getMimeType($attachment->file_type);
            });
            
            return response()->json([
                'success' => true,
                'achievement' => $achievement,
                'competitions' => $competitions
            ]);
        }

        return view('Mahasiswa.achievements.components.edit-achievement', compact('achievement', 'competitions'));
    }

    // Function to update the specified achievement in storage
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|string',
            'level' => 'required|string',
            'date' => 'required|date',
            'description' => 'required|string',
            'competition_id' => 'nullable|exists:competitions,id',
            'attachments' => 'nullable',
            'attachments.*' => 'mimes:pdf,jpg,jpeg,png|max:2300',
        ]);

        try {
            $achievement = AchievementModel::where('user_id', auth()->id())->findOrFail($id);
            $achievement->fill($validatedData);

            if (!$achievement->save()) {
                throw new \Exception('Gagal menyimpan perubahan.');
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('attachments', $fileName, 'public');
                    $fileType = strtolower($file->getClientOriginalExtension());
                    $fileSize = $file->getSize();

                    AttachmentModel::create([
                        'achievement_id' => $achievement->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                    ]);
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prestasi berhasil diperbarui'
                ]);
            }

            return redirect()->route('Mahasiswa.achievements.index')->with('status', 'Prestasi berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah prestasi: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()->with('error', 'Gagal mengubah prestasi: ' . $e->getMessage());
        }
    }

    // Function to remove the specified achievement from storage
    public function destroy(string $id)
    {
        try {
            $achievement = AchievementModel::where('user_id', auth()->id())->findOrFail($id);

            if ($achievement->attachments) {
                foreach ($achievement->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }

            $achievement->delete();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prestasi berhasil dihapus'
                ]);
            }
            
            return redirect()->route('Mahasiswa.achievements.index')->with('status', 'Prestasi berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus prestasi: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('Mahasiswa.achievements.index')->with('error', 'Gagal menghapus prestasi: ' . $e->getMessage());
        }
    }

    // Function to get MIME type from file extension
    private function getMimeType($fileType)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        
        return $mimeTypes[$fileType] ?? 'application/octet-stream';
    }
}
