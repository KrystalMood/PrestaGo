<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ahp_calculation_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('competition_id'); 
            $table->string('calculation_type', 50)->default('interest'); 
            $table->float('final_score', 10, 6); 
            $table->float('consistency_ratio', 10, 6)->nullable(); 
            $table->boolean('is_consistent')->default(false); 
            $table->json('calculation_details')->nullable(); 
            $table->timestamp('calculated_at'); 
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            
            $table->unique(
                ['user_id', 'competition_id', 'calculation_type'],
                'ahp_calc_unique'
            );
        });
        
        Schema::table('recommendations', function (Blueprint $table) {
            $table->foreign('ahp_result_id')->references('id')->on('ahp_calculation_results')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key first before dropping the table
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropForeign(['ahp_result_id']);
        });
        
        Schema::dropIfExists('ahp_calculation_results');
    }
};
