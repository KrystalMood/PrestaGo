<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerificationModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = VerificationModel::with('user');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $verifications = $query->latest()->paginate(10);
        
        $totalVerifications = VerificationModel::count();
        $pendingVerifications = VerificationModel::where('status', 'pending')->count();
        $approvedVerifications = VerificationModel::where('status', 'approved')->count();
        
        if ($request->ajax()) {
            if ($request->has('ajax') && $request->ajax == 1) {
                $tableHtml = view('admin.verification.components.tables', compact('verifications'))->render();
                $paginationHtml = view('admin.components.tables.pagination', compact('verifications'))->render();
                
                return response()->json([
                    'tableHtml' => $tableHtml,
                    'paginationHtml' => $paginationHtml,
                    'currentPage' => $verifications->currentPage()
                ]);
            }
            
            return response()->json([
                'verifications' => $verifications,
                'stats' => [
                    'totalVerifications' => $totalVerifications,
                    'pendingVerifications' => $pendingVerifications,
                    'approvedVerifications' => $approvedVerifications,
                ]
            ]);
        }
        
        return view('admin.verification.index', compact('verifications', 'totalVerifications', 'pendingVerifications', 'approvedVerifications'));
    }

    public function show($id)
    {
        try {
            $verification = VerificationModel::with('user', 'documents')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'verification' => [
                    'id' => $verification->id,
                    'user' => [
                        'id' => $verification->user->id,
                        'name' => $verification->user->name,
                        'email' => $verification->user->email,
                        'photo' => $verification->user->photo 
                            ? asset('storage/' . $verification->user->photo) 
                            : 'https://ui-avatars.com/api/?name=' . urlencode($verification->user->name) . '&background=4338ca&color=fff',
                    ],
                    'status' => $verification->status,
                    'notes' => $verification->notes,
                    'rejection_reason' => $verification->rejection_reason,
                    'created_at' => $verification->created_at,
                    'updated_at' => $verification->updated_at,
                    'documents' => $verification->documents->map(function ($doc) {
                        return [
                            'id' => $doc->id,
                            'name' => $doc->name,
                            'path' => $doc->path,
                            'url' => asset('storage/' . $doc->path)
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $verification = VerificationModel::findOrFail($id);
            
            if (!$request->has('status')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status verifikasi tidak diberikan'
                ], 400);
            }
            
            $status = $request->status;
            
            if (!in_array($status, ['approved', 'rejected'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status verifikasi tidak valid'
                ], 400);
            }
            
            if ($status === 'rejected' && !$request->has('reason')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alasan penolakan diperlukan'
                ], 400);
            }
            
            $verification->status = $status;
            
            if ($status === 'rejected') {
                $verification->rejection_reason = $request->reason;
            }
            
            $verification->verified_by = Auth::id();
            $verification->verified_at = now();
            
            $verification->save();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $status === 'approved' 
                        ? 'Verifikasi berhasil disetujui' 
                        : 'Verifikasi berhasil ditolak',
                    'verification' => $verification
                ]);
            }
            
            return redirect()->route('admin.verification.index')
                ->with('success', $status === 'approved' 
                    ? 'Verifikasi berhasil disetujui' 
                    : 'Verifikasi berhasil ditolak');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status verifikasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.verification.show', $id)
                ->with('error', 'Gagal memperbarui status verifikasi: ' . $e->getMessage());
        }
    }
} 