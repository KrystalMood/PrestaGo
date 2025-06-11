<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CompetitionStatusController extends Controller
{
    public function updateStatuses(Request $request)
    {
        $today = Carbon::today();
        $updatedCompetitions = [];
        $updatedCount = 0;
        
        $competitions = CompetitionModel::all();
        
        foreach ($competitions as $competition) {
            $startDate = $competition->start_date;
            $endDate = $competition->end_date;
            $currentStatus = $competition->status;
            $newStatus = null;
            
            if ($startDate && $endDate) {
                if ($today->lt($startDate) && $currentStatus !== 'upcoming') {
                    $newStatus = 'upcoming';
                } elseif ($today->gte($startDate) && $today->lte($endDate) && $currentStatus !== 'active') {
                    $newStatus = 'active';
                } elseif ($today->gt($endDate) && $currentStatus !== 'completed') {
                    $newStatus = 'completed';
                }
                
                if ($newStatus) {
                    $competition->status = $newStatus;
                    $competition->save();
                    
                    $updatedCompetitions[] = [
                        'id' => $competition->id,
                        'name' => $competition->name,
                        'old_status' => $currentStatus,
                        'new_status' => $newStatus
                    ];
                    
                    $updatedCount++;
                }
            }
        }
        
        if ($updatedCount > 0) {
            Log::info("Auto-updated {$updatedCount} competition statuses via API.");
        }
        
        return response()->json([
            'success' => true,
            'message' => $updatedCount > 0 
                ? "{$updatedCount} kompetisi berhasil diperbarui statusnya." 
                : "Semua status kompetisi sudah sesuai dengan tanggal saat ini.",
            'updated_count' => $updatedCount,
            'updated_competitions' => $updatedCompetitions
        ]);
    }
}
