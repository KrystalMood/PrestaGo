<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Services\AuthService;
use Illuminate\Http\Request;

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
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($this->loginAction->execute($request)) {
            return redirect()->intended('/dashboard')
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

    public function dashboard()
    {
        return view('dashboard.main');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar.');
    }
}
