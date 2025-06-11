<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'require_approval' => true,
        ];
        
        return view('admin.settings.security', compact('settings'));
    }
    
    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'require_approval' => 'boolean',
        ]);
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan keamanan berhasil diperbarui.');
    }
    
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini tidak sesuai.');
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Password berhasil diubah.');
    }
}