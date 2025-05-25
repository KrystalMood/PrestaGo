<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        $settings = [
            'app_name' => config('app.name'),
            'admin_email' => config('app.admin_email', 'admin@prestago.com'),
            'logo_path' => config('app.logo_path', '/images/logo.png'),
        ];
        
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        
        if ($request->hasFile('logo')) {
            
        }

        return redirect()->route('admin.settings.general')
            ->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    public function email()
    {
        $settings = [
            'mail_driver' => config('mail.mailer'),
            'mail_host' => config('mail.host'),
            'mail_port' => config('mail.port'),
            'mail_from_address' => config('mail.from.address'),
        ];
        
        return view('admin.settings.email', compact('settings'));
    }

    public function security()
    {
        $settings = [
            'password_min_length' => 8,
            'session_lifetime' => config('session.lifetime'),
            'require_approval' => true,
        ];
        
        return view('admin.settings.security', compact('settings'));
    }

    public function display()
    {
        $settings = [
            'theme' => 'default',
            'items_per_page' => 10,
            'dashboard_widgets' => ['achievements', 'competitions', 'users'],
        ];
        
        return view('admin.settings.display', compact('settings'));
    }
}