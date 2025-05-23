<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;

class  CompetitionContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CompetitionModel::with(['addedBy', 'period', 'skills'])->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('competition_name	', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10)->withQueryString();

        $no = 1;
        if ($request->ajax() || $request->has('ajax')) {
            $tableView = view('Mahasiswa.competitions.components.tables', compact('data'))->render();
            $paginationView = view('Mahasiswa.components.tables.pagination', ['data' => $data])->render();

            return response()->json([
                'success' => true,
                'table' => $tableView,
                'pagination' => $paginationView
            ]);
        }

        return view('Mahasiswa.competitions.index', compact('data', 'no'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
