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
                ->with('success', 'Login successful! Welcome back.');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->except('password'))
            ->with('error', 'Login failed! Please check your email and password again.');
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
                ->with('success', 'Registration successful! Please log in.');
        }

        return back()
            ->withErrors($result['errors'] ?? ['email' => 'Registration failed.'])
            ->withInput($request->except('password'))
            ->with('error', 'Registration Failed');
    }

    public function dashboard()
    {
        return view('dashboard.main');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('info', 'You have been logged out successfully.');
    }
}
