<?php

namespace App\Actions\Auth;

use App\Events\UserLoggedIn;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginUserAction
{
    protected $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    /**
     * Handle the login process with additional functionality.
     *
     * @param Request $request
     * @return bool
     */
    public function execute(Request $request): bool
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($this->authService->attemptLogin($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $this->logLoginActivity($request);
            
            event(new UserLoggedIn($request->user()));
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Track login activity.
     *
     * @param Request $request
     * @return void
     */
    protected function logLoginActivity(Request $request): void
    {
        // Implementasi untuk lacak detail login
        // Bisa IP, info perangkat, timestamp, dll.
        // Contoh:
        // LoginActivity::create([
        //     'user_id' => $request->user()->id,
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        // ]);
    }
}