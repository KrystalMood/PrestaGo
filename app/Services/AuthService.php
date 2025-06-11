<?php

namespace App\Services;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }
    
    /**
     * Register a new user.
     *
     * @param array $userData
     * @param string $role
     * @return array
     */
    public function registerUser(array $userData, string $role = 'MHS'): array
    {
        try {
            $level = LevelModel::where('level_kode', $role)->first();
            
            if (!$level) {
                return [
                    'success' => false,
                    'errors' => ['level' => 'Tipe akun tidak valid.'],
                    'user' => null
                ];
            }
            
            $user = UserModel::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'level_id' => $level->level_id,
                'password' => Hash::make($userData['password']),
            ]);
            
            return [
                'success' => true,
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ['email' => 'Gagal membuat pengguna.'],
                'user' => null
            ];
        }
    }
    
    /**
     * Check if a user has a specific role
     *
     * @param UserModel $user
     * @param string|array $roles
     * @return bool
     */
    public function hasRole(UserModel $user, $roles): bool
    {
        if (is_string($roles)) {
            return $user->hasRole($roles);
        }
        
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Log the user out.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}