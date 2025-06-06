<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudyProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = StudyProgramModel::query();
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('faculty', 'LIKE', "%{$search}%");
            });
        }
        
        $programs = $query->orderBy('name')->paginate(10);
        
        $totalPrograms = StudyProgramModel::count();
        $totalFaculties = StudyProgramModel::distinct('faculty')->count('faculty');
        
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'success' => true,
                'table' => view('admin.programs.components.tables', compact('programs'))->render(),
                'pagination' => view('admin.components.tables.pagination', ['data' => $programs])->render(),
                'stats' => [
                    'totalPrograms' => $totalPrograms,
                    'totalFaculties' => $totalFaculties
                ]
            ]);
        }
        
        return view('admin.programs.index', compact(
            'programs',
            'totalPrograms',
            'totalFaculties'
        ));
    }

    public function create()
    {
        return view('admin.programs.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:study_programs,name',
            'code' => 'required|string|max:20|unique:study_programs,code',
            'faculty' => 'required|string|max:255',
            'degree_level' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validasi gagal'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $program = StudyProgramModel::create([
            'name' => $request->name,
            'code' => $request->code,
            'faculty' => $request->faculty,
            'degree_level' => $request->degree_level,
            'accreditation' => $request->accreditation,
            'year_established' => $request->year_established,
            'description' => $request->description,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $program,
                'message' => 'Program studi berhasil ditambahkan'
            ]);
        }

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $program = StudyProgramModel::findOrFail($id);
        
        $totalStudents = $program->users()->count();
        
        $program->updated_at_formatted = $program->updated_at->format('d M Y H:i');
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $program,
                'message' => 'Program studi berhasil ditemukan'
            ]);
        }
        
        return view('admin.programs.show', compact('program', 'totalStudents'));
    }

    public function edit($id)
    {
        $program = StudyProgramModel::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $program,
                'message' => 'Program studi berhasil ditemukan'
            ]);
        }
        
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = StudyProgramModel::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:study_programs,name,' . $id,
            'code' => 'required|string|max:20|unique:study_programs,code,' . $id,
            'faculty' => 'required|string|max:255',
            'degree_level' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validasi gagal'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $program->update([
            'name' => $request->name,
            'code' => $request->code,
            'faculty' => $request->faculty,
            'degree_level' => $request->degree_level,
            'accreditation' => $request->accreditation,
            'year_established' => $request->year_established,
            'description' => $request->description,
        ]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $program,
                'message' => 'Program studi berhasil diperbarui'
            ]);
        }

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $program = StudyProgramModel::findOrFail($id);
        
        if ($program->users()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program studi tidak dapat dihapus karena masih memiliki data mahasiswa terkait.'
                ], 400);
            }
            
            return redirect()->route('admin.programs.index')
                ->with('error', 'Program studi tidak dapat dihapus karena masih memiliki data mahasiswa terkait.');
        }
        
        $program->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil dihapus'
            ]);
        }
        
        return redirect()->route('admin.programs.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }

    public function getJson($id)
    {
        try {
            $program = StudyProgramModel::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $program->id,
                    'name' => $program->name,
                    'code' => $program->code,
                    'faculty' => $program->faculty,
                    'degree_level' => $program->degree_level
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Program studi tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
} 