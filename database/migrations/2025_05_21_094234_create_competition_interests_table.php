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
        Schema::create('competition_interests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('interest_area_id');
            $table->float('relevance_score')->default(1.0);
            $table->float('importance_factor')->default(1.0);
            $table->boolean('is_mandatory')->default(false);
            $table->integer('minimum_level')->default(0);
            $table->timestamps();
            
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('interest_area_id')->references('id')->on('interest_areas')->onDelete('cascade');
            
            $table->unique(['competition_id', 'interest_area_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_interests');
    }
};
