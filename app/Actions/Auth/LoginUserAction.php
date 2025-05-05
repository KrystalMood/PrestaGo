<?php

namespace App\Actions\Auth;

use App\Events\UserLoggedIn;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            
            if ($this->authService->attemptLogin($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                
                $user = $request->user();
                if (!$user->level || !in_array($user->getRole(), ['ADM', 'MHS', 'DSN'])) {
                    $this->authService->logout();
                    throw ValidationException::withMessages([
                        'email' => 'Akun tidak memiliki hak akses yang valid.',
                    ]);
                }
                
                $this->logLoginActivity($request);
                
                event(new UserLoggedIn($user));
                
                return true;
            }
            
            return false;
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return false;
        }
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
        //     'login_time' => now(),
        // ]);
    }
}