<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecommendationModel;
use App\Http\Controllers\Admin\RecommendationController;

class RegenerateDSSCalculations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendations:regenerate-dss {--id= : Specific recommendation ID to regenerate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate DSS calculation details for recommendations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recommendationId = $this->option('id');
        
        if ($recommendationId) {
            $recommendation = RecommendationModel::find($recommendationId);
            
            if (!$recommendation) {
                $this->error("Recommendation with ID {$recommendationId} not found.");
                return 1;
            }
            
            $this->info("Regenerating DSS calculation for recommendation ID: {$recommendationId}");
            $this->regenerateCalculation($recommendation);
        } else {
            $this->info('Regenerating DSS calculations for all recommendations without calculation details...');
            
            $studentRecommendations = RecommendationModel::where('for_lecturer', false)
                ->whereNull('ahp_result_id')
                ->get();
            
            $this->info("Found {$studentRecommendations->count()} student recommendations without AHP results.");
            
            foreach ($studentRecommendations as $recommendation) {
                $this->info("Processing student recommendation ID: {$recommendation->id}");
                $this->regenerateCalculation($recommendation);
            }
            
            $lecturerRecommendations = RecommendationModel::where('for_lecturer', true)
                ->whereNull('wp_result_id')
                ->get();
            
            $this->info("Found {$lecturerRecommendations->count()} lecturer recommendations without WP results.");
            
            foreach ($lecturerRecommendations as $recommendation) {
                $this->info("Processing lecturer recommendation ID: {$recommendation->id}");
                $this->regenerateCalculation($recommendation);
            }
            
            $this->info('DSS calculation regeneration completed.');
        }
        
        return 0;
    }
    
    /**
     * Regenerate calculation for a specific recommendation
     */
    private function regenerateCalculation(RecommendationModel $recommendation)
    {
        try {
            $controller = app(RecommendationController::class);
            
            $this->info("Recommendation ID: {$recommendation->id}");
            $this->info("For lecturer: " . ($recommendation->for_lecturer ? 'Yes' : 'No'));
            $this->info("AHP result ID: " . ($recommendation->ahp_result_id ?? 'null'));
            $this->info("WP result ID: " . ($recommendation->wp_result_id ?? 'null'));
            
            if ($recommendation->for_lecturer) {
                $method = new \ReflectionMethod($controller, 'generateWPCalculationForRecommendation');
                $method->setAccessible(true);
                $result = $method->invoke($controller, $recommendation);
                
                if ($result) {
                    $this->info("Successfully regenerated WP calculation for lecturer recommendation ID: {$recommendation->id}");
                    $this->info("New WP result ID: {$result->id}");
                } else {
                    $this->warn("Failed to regenerate WP calculation for lecturer recommendation ID: {$recommendation->id}");
                }
            } else {
                $method = new \ReflectionMethod($controller, 'generateAHPCalculationForRecommendation');
                $method->setAccessible(true);
                $result = $method->invoke($controller, $recommendation);
                
                if ($result) {
                    $this->info("Successfully regenerated AHP calculation for student recommendation ID: {$recommendation->id}");
                    $this->info("New AHP result ID: {$result->id}");
                } else {
                    $this->warn("Failed to regenerate AHP calculation for student recommendation ID: {$recommendation->id}");
                }
            }
            
            $updatedRecommendation = RecommendationModel::find($recommendation->id);
            $this->info("After regeneration - AHP result ID: " . ($updatedRecommendation->ahp_result_id ?? 'null'));
            $this->info("After regeneration - WP result ID: " . ($updatedRecommendation->wp_result_id ?? 'null'));
        } catch (\Exception $e) {
            $this->error("Error regenerating calculation for recommendation ID {$recommendation->id}: {$e->getMessage()}");
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
} 