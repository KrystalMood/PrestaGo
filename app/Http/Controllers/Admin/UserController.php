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
            'level_id' => 'required|exists:level,level_id',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['password'] = Hash::make($validated['password']);

        UserModel::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $roles = LevelModel::all();
        
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
                Rule::unique('users')->ignore($user->users_id, 'users_id'),
            ],
            'level_id' => 'required|exists:level,level_id',
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

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->users_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleName(),
                    'role_code' => $user->getRole(),
                    'created_at' => $user->created_at->format('d M Y, H:i'),
                    'photo' => $user->photo ? asset('storage/' . $user->photo) : 
                        'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4338ca&color=fff',
                ]
            ]);
        }
        
        return abort(404);
    }

    public function destroy(string $id)
    {
        $user = UserModel::findOrFail($id);
        
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }
}
