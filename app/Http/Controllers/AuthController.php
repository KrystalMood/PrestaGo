<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function postlogin(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $credentials = $request->only('email', 'password');
            if (auth()->attempt($credentials)) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Login Gagal Cek Kembali Email dan Password']);
            }
        }
    }
    public function register()
    {
        return view('auth.register');
    }
    public function postregister(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $validate = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if($validate){
                UserModel::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
                return response()->json(['success' => true, 'message' => 'Register Berhasil']);
            }else{
                return response()->json(['success' => false, 'message' => 'Register Gagal']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada sistem']);
    }
}
