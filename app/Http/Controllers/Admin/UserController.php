<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelModel;
use App\Models\UserModel;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = UserModel::with('level');
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('level', function($q) use ($request) {
                $q->where('level_kode', $request->role);
            });
        }
        
        $users = $query->latest()->paginate(10);
        $roles = LevelModel::all();
        
        $totalUsers = UserModel::count();
        $newUsers = UserModel::whereDate('created_at', '>=', now()->subDays(30))->count();
        
        if ($request->ajax()) {
            if ($request->has('ajax') && $request->ajax == 1) {
                $tableHtml = view('admin.users.components.tables', compact('users'))->render();
                $paginationHtml = view('admin.components.tables.pagination', compact('users'))->render();
                
                return response()->json([
                    'tableHtml' => $tableHtml,
                    'paginationHtml' => $paginationHtml,
                    'currentPage' => $users->currentPage()
                ]);
            }
            
            return response()->json([
                'users' => $users,
                'roles' => $roles,
                'stats' => [
                    'totalUsers' => $totalUsers,
                    'newUsers' => $newUsers,
                ]
            ]);
        }
        
        return view('admin.users.index', compact('users', 'roles', 'totalUsers', 'newUsers'));
    }

    public function create()
    {
        $roles = LevelModel::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'level_id' => 'required|exists:level,id',
            'photo' => 'nullable|image|max:2048',
            'nim' => 'nullable|string|max:20',
            'nip' => 'nullable|string|max:20',
            'program_studi_id' => 'nullable|exists:study_programs,id',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = UserModel::create($validated);
        
        ActivityService::log(
            'green',
            'Pengguna baru ditambahkan: ' . $user->name,
            'create',
            'user',
            'admin'
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan!',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $roles = LevelModel::all();
        
        if (request()->ajax()) {
            return response()->json($user);
        }
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = UserModel::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id, 'id'),
            ],
            'level_id' => 'required|exists:level,id',
            'photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
            'nim' => 'nullable|string|max:20',
            'nip' => 'nullable|string|max:20',
            'program_studi_id' => 'nullable|exists:study_programs,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $photoPath = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $photoPath;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        
        ActivityService::log(
            'blue',
            'Pengguna diperbarui: ' . $user->name,
            'update',
            'user',
            'admin'
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui!',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function show(string $id)
    {
        try {
            $user = UserModel::with('level')->findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->getRoleName(),
                        'role_code' => $user->getRole(),
                        'nim' => $user->nim,
                        'nip' => $user->nip,
                        'program_studi_id' => $user->program_studi_id,
                        'created_at' => $user->created_at->format('d M Y, H:i'),
                        'photo' => $user->photo 
                            ? asset('storage/' . $user->photo) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4338ca&color=fff',
                    ]
                ]);
            }
            
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat detail pengguna: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal memuat detail pengguna: ' . $e->getMessage());
        }
    }
    
    public function getDetails(string $id)
    {
        try {
            $user = UserModel::with('level')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'level_id' => $user->level_id,
                    'role' => $user->getRoleName(),
                    'role_code' => $user->getRole(),
                    'nim' => $user->nim,
                    'nip' => $user->nip,
                    'program_studi_id' => $user->program_studi_id,
                    'created_at' => $user->created_at->format('d M Y, H:i'),
                    'photo' => $user->photo 
                        ? asset('storage/' . $user->photo) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4338ca&color=fff',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail pengguna: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $user = UserModel::findOrFail($id);
        
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        
        $userName = $user->name;
        
        $user->delete();
        
        ActivityService::log(
            'red',
            'Pengguna dihapus: ' . $userName,
            'delete',
            'user',
            'admin'
        );

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
    
    public function export()
    {
        $users = UserModel::with('level')->get();
        
        $filename = 'users_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 
                'Nama',
                'Email',
                'Peran',
                'Tanggal Registrasi'
            ]);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->getRoleName(),
                    $user->created_at->format('d/m/Y H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download CSV template for user import.
     */
    public function importTemplate()
    {
        $filename = 'users_import_template.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Header columns expected during import
            fputcsv($file, [
                'name',
                'email',
                'password',
                'level_kode',
                'nim',
                'nip',
                'program_studi_id',
            ]);

            // Example row
            fputcsv($file, [
                'John Doe',
                'john.doe@example.com',
                'password123',
                'MHS',
                '21081010001',
                '',
                '1',
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Handle import of users from a CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();

        // Open CSV file
        if (!$handle = fopen($path, 'r')) {
            return back()->with('error', 'Tidak dapat membaca berkas yang diunggah.');
        }

        $header = fgetcsv($handle);

        $requiredColumns = ['name', 'email', 'password', 'level_kode'];
        foreach ($requiredColumns as $column) {
            if (!in_array($column, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom $column tidak ditemukan pada berkas.");
            }
        }

        $row = 1; // considering header row as 0
        $imported = 0;
        $errors = [];

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $rowData = array_combine($header, $data);

            if (empty($rowData['name']) || empty($rowData['email']) || empty($rowData['password']) || empty($rowData['level_kode'])) {
                $errors[] = "Baris $row: data wajib tidak lengkap.";
                continue;
            }

            // Check level kode validity
            $level = LevelModel::where('level_kode', $rowData['level_kode'])->first();
            if (!$level) {
                $errors[] = "Baris $row: level_kode {$rowData['level_kode']} tidak valid.";
                continue;
            }

            // Skip duplicate email
            if (UserModel::where('email', $rowData['email'])->exists()) {
                $errors[] = "Baris $row: email {$rowData['email']} sudah digunakan.";
                continue;
            }

            try {
                UserModel::create([
                    'name' => $rowData['name'],
                    'email' => $rowData['email'],
                    'password' => Hash::make($rowData['password']),
                    'level_id' => $level->id,
                    'nim' => $rowData['nim'] ?? null,
                    'nip' => $rowData['nip'] ?? null,
                    'program_studi_id' => $rowData['program_studi_id'] ?? null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris $row: gagal menambah pengguna. {$e->getMessage()}";
            }
        }

        fclose($handle);

        $message = "Berhasil mengimpor $imported pengguna.";
        if (!empty($errors)) {
            $message .= ' Beberapa baris gagal diimpor.';
            return back()->with('warning', $message)->with('import_errors', $errors);
        }

        return back()->with('success', $message);
    }
}
