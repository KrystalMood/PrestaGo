<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = UserModel::with('level');
        
        if ($request->has('search')) {
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
        $activeUsers = $totalUsers;
        
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
                    'activeUsers' => $activeUsers,
                ]
            ]);
        }
        
        return view('admin.users.index', compact('users', 'roles', 'totalUsers', 'newUsers', 'activeUsers'));
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
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = UserModel::create($validated);

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
        
        $user->delete();

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
}
