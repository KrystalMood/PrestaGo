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
        Schema::create('sub_competition_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_competition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->integer('importance_level')->default(1);
            $table->float('weight_value')->default(1.0); 
            $table->string('criterion_type', 50)->default('benefit'); 
            $table->float('ahp_priority')->default(0.0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_competition_skills');
    }
};
