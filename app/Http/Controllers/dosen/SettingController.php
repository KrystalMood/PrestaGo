<?php

namespace App\Http\Controllers\dosen;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(){
        return view("Dosen.settings.index");
    }

    public function profile(){

        $user = UserModel::with('program_studi')
            ->findOrFail(auth()->id());

        return view("Dosen.profile.index", compact('user'));
    }

    public function updateProfile(Request $request, string $id){

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:255|unique:users,nim,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = UserModel::findOrFail($id);

        if ($user->id != auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah profil ini.');
        }

        $user->name = $validatedData['name'];
        $user->nim = $validatedData['nim'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }

            $photoPath = $request->file('photo')->store('photos', 'public');
            $user->photo = $photoPath;
        }

        $user->save();

        return redirect()->route('lecturer.profile.profile')->with('success', 'Profil berhasil diperbarui.');

    }
}
