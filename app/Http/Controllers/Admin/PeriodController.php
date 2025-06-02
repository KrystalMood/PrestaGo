<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodModel::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        
        $periods = $query->latest()->paginate(10);
        
        $totalPeriods = PeriodModel::count();
        
        if ($request->ajax() || $request->has('ajax')) {
            $table = View::make('admin.periods.components.tables', compact('periods'))->render();
            $pagination = View::make('admin.components.tables.pagination', ['data' => $periods])->render();
            
            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'stats' => [
                    'totalPeriods' => $totalPeriods,
                ]
            ]);
        }
        
        return view('admin.periods.index', compact('periods', 'totalPeriods'));
    }

    public function create()
    {
        return view('admin.periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:periods',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $overlappingPeriod = PeriodModel::where(function($query) use ($request) {
            $query->where('start_date', '<=', $request->start_date)
                  ->where('end_date', '>=', $request->start_date);
        })->orWhere(function($query) use ($request) {
            $query->where('start_date', '<=', $request->end_date)
                  ->where('end_date', '>=', $request->end_date);
        })->orWhere(function($query) use ($request) {
            $query->where('start_date', '>=', $request->start_date)
                  ->where('end_date', '<=', $request->end_date);
        })->first();
        
        if ($overlappingPeriod) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'date_overlap' => ['Periode baru tidak dapat bertabrakan dengan periode yang sudah ada. Periode "' . $overlappingPeriod->name . '" sudah ada pada rentang tanggal tersebut.']
                ]
            ], 422);
        }

        $period = PeriodModel::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil ditambahkan!',
                'data' => $period
            ]);
        }

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $period = PeriodModel::findOrFail($id);
        
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, string $id)
    {
        $period = PeriodModel::findOrFail($id);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('periods')->ignore($period->id),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $overlappingPeriod = PeriodModel::where('id', '!=', $id)
            ->where(function($query) use ($request) {
                $query->where('start_date', '<=', $request->start_date)
                      ->where('end_date', '>=', $request->start_date);
            })->orWhere(function($query) use ($request, $id) {
                $query->where('id', '!=', $id)
                      ->where('start_date', '<=', $request->end_date)
                      ->where('end_date', '>=', $request->end_date);
            })->orWhere(function($query) use ($request, $id) {
                $query->where('id', '!=', $id)
                      ->where('start_date', '>=', $request->start_date)
                      ->where('end_date', '<=', $request->end_date);
            })->first();
        
        if ($overlappingPeriod) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'date_overlap' => ['Periode tidak dapat bertabrakan dengan periode yang sudah ada. Periode "' . $overlappingPeriod->name . '" sudah ada pada rentang tanggal tersebut.']
                ]
            ], 422);
        }

        $period->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil diperbarui!',
                'data' => $period
            ]);
        }

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode berhasil diperbarui!');
    }

    public function show(string $id)
    {
        $period = PeriodModel::with('competitions')->findOrFail($id);
        
        if (request()->ajax() || request()->wantsJson()) {
            $now = now();
            $status = 'upcoming';
            
            if ($period->start_date <= $now && $period->end_date >= $now) {
                $status = 'active';
            } elseif ($period->end_date < $now) {
                $status = 'completed';
            }
            
            return response()->json([
                'id' => $period->id,
                'name' => $period->name,
                'start_date' => $period->start_date->format('d M Y'),
                'end_date' => $period->end_date->format('d M Y'),
                'start_date_raw' => $period->start_date->format('Y-m-d'),
                'end_date_raw' => $period->end_date->format('Y-m-d'),
                'competitions_count' => $period->competitions->count(),
                'created_at' => $period->created_at->format('d M Y, H:i'),
                'updated_at' => $period->updated_at->format('d M Y, H:i'),
                'status' => $status,
            ]);
        }
        
        return view('admin.periods.show', compact('period'));
    }

    public function destroy(string $id)
    {
        $period = PeriodModel::findOrFail($id);
        
        if ($period->competitions()->count() > 0) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode tidak dapat dihapus karena memiliki kompetisi terkait!'
                ], 422);
            }
            
            return redirect()->route('admin.periods.index')
                ->with('error', 'Periode tidak dapat dihapus karena memiliki kompetisi terkait!');
        }
        
        $period->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode berhasil dihapus!');
    }

    public function export()
    {
        // Example export functionality
        return response()->download(public_path('sample-export.csv'));
    }
} 