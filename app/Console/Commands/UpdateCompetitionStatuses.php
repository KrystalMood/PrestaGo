<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompetitionModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateCompetitionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-competition-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update competition statuses based on current date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting automatic competition status update...');
        
        $today = Carbon::today();
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
                    $this->info("Updating competition ID {$competition->id} '{$competition->name}' status from '{$currentStatus}' to '{$newStatus}'");
                    
                    $competition->status = $newStatus;
                    $competition->save();
                    
                    $updatedCount++;
                }
            }
        }
        
        if ($updatedCount > 0) {
            $this->info("Successfully updated {$updatedCount} competition statuses.");
            Log::info("Auto-updated {$updatedCount} competition statuses based on current date.");
        } else {
            $this->info('All competition statuses are up to date.');
        }
        
        return 0;
    }
}
