<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $request->user();
        $user_role = $user->getRole();

        if (empty($roles) || in_array($user_role, $roles)) {
            return $next($request);
        }

        switch ($user_role) {
            case 'ADM':
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            case 'MHS':
                return redirect()->route('Mahasiswa.dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            case 'DSN':
                return redirect()->route('lecturer.dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            default:
                return redirect()->route('login')
                    ->with('error', 'Akun Anda tidak memiliki peran yang valid. Silakan hubungi administrator.');
        }
    }
}
