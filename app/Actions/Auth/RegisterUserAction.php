<?php

namespace App\Actions\Auth;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterUserAction
{
    protected $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    /**
     * Execute the registration action.
     *
     * @param Request $request
     * @return array
     */
    public function execute(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'sometimes|in:MHS,DSN',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
            ];
        }

        try {
            $role = $request->input('role', 'MHS');
            
            $result = $this->authService->registerUser(
                $validator->validated(),
                $role
            );
            
            if (!$result['success']) {
                return $result;
            }
            
            return [
                'success' => true,
                'user' => $result['user'],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ['email' => 'Registrasi gagal: ' . $e->getMessage()],
            ];
        }
    }
}