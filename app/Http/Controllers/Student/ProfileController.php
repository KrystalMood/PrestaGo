<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InterestAreaModel;
use App\Models\SkillModel;
use App\Models\StudyProgramModel;
use App\Models\UserModel;
use App\Models\UserInterestModel;
use App\Models\UserSkillModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $skills = SkillModel::orderBy('category')->orderBy('name')->get();
        $interests = InterestAreaModel::where('is_active', true)->orderBy('display_order')->get();
        $studyPrograms = StudyProgramModel::orderBy('name')->get();
        
        $userSkills = $user->skills()->get();
        $userInterests = $user->interests()->get();
        
        return view('student.profile.index', compact(
            'user',
            'skills',
            'interests',
            'studyPrograms',
            'userSkills',
            'userInterests'
        ));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        // We don't update NIM and program_studi_id as they should be readonly
        // The existing values in the database will be preserved
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::exists('public/photos/' . $user->photo)) {
                Storage::delete('public/photos/' . $user->photo);
            }
            
            $photo = $request->file('photo');
            $filename = time() . '_' . $user->id . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $filename);
            
            $user->photo = $filename;
        }
        
        $user->save();
        
        return back()->with('status', 'Profil berhasil diperbarui!');
    }
    
    public function updateSkills(Request $request)
    {
        $user = Auth::user();
        
        if (isset($request->is_empty) && $request->is_empty === true) {
            UserSkillModel::where('user_id', $user->id)->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Semua keterampilan berhasil dihapus!'
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.skill_id' => 'required|exists:skills,id',
            'skills.*.proficiency_level' => 'required|integer|min:1|max:5',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        UserSkillModel::where('user_id', $user->id)->delete();
        
        foreach ($request->skills as $skill) {
            UserSkillModel::create([
                'user_id' => $user->id,
                'skill_id' => $skill['skill_id'],
                'proficiency_level' => $skill['proficiency_level'],
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Keterampilan berhasil diperbarui!'
        ]);
    }
    
    public function updateInterests(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'interests' => 'required|array',
            'interests.*.interest_area_id' => 'required|exists:interest_areas,id',
            'interests.*.interest_level' => 'required|integer|min:1|max:5',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        UserInterestModel::where('user_id', $user->id)->delete();
        
        foreach ($request->interests as $interest) {
            UserInterestModel::create([
                'user_id' => $user->id,
                'interest_area_id' => $interest['interest_area_id'],
                'interest_level' => $interest['interest_level'],
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Bidang minat berhasil diperbarui!'
        ]);
    }
    
    public function getSkills()
    {
        $skills = SkillModel::orderBy('category')->orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'count' => $skills->count(),
            'skills' => $skills
        ]);
    }
    
    public function getInterestAreas()
    {
        $interests = InterestAreaModel::where('is_active', true)->orderBy('display_order')->get();
        
        return response()->json([
            'success' => true,
            'count' => $interests->count(),
            'interests' => $interests
        ]);
    }
} 