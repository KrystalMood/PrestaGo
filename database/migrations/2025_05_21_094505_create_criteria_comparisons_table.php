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
        Schema::create('criteria_comparisons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('criteria_row_id'); 
            $table->unsignedBigInteger('criteria_column_id'); 
            $table->float('comparison_value'); 
            $table->string('comparison_type', 50)->default('interest'); 
            $table->timestamp('last_updated')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps();
            
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                
            $table->unique(['criteria_row_id', 'criteria_column_id', 'comparison_type'], 'criteria_comp_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_comparisons');
    }
};
