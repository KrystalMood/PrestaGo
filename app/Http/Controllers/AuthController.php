<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    protected $loginAction;
    protected $registerAction;

    public function __construct(
        AuthService $authService,
        LoginUserAction $loginAction,
        RegisterUserAction $registerAction
    ) {
        $this->authService = $authService;
        $this->loginAction = $loginAction;
        $this->registerAction = $registerAction;
    }

    public function login()
    {
        if(Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($this->loginAction->execute($request)) {
            return $this->redirectBasedOnRole(Auth::user())
                ->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()
            ->withErrors(['email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.'])
            ->withInput($request->except('password'))
            ->with('error', 'Login gagal! Silakan periksa kembali email dan kata sandi Anda.');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function postregister(Request $request)
    {
        $result = $this->registerAction->execute($request);

        if ($result['success']) {
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan masuk.');
        }

        return back()
            ->withErrors($result['errors'] ?? ['email' => 'Registrasi gagal.'])
            ->withInput($request->except('password'))
            ->with('error', 'Registrasi Gagal');
    }

    /**
     * Redirect user based on their role
     * 
     * @param \App\Models\UserModel $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole($user)
    {
        $role = $user->getRole();
        
        switch ($role) {
            case 'ADM':
                return redirect()->route('admin.dashboard');
            case 'MHS':
                return redirect()->route('student.dashboard');
            case 'DSN':
                return redirect()->route('lecturer.dashboard');
            default:
                return redirect()->route('login')
                    ->with('error', 'Tidak dapat menentukan hak akses Anda. Silakan hubungi administrator.');
        }
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function lecturerDashboard()
    {
        return view('lecturer.dashboard');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar.');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
}
