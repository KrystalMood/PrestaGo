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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('match_score', 5, 2);
            $table->string('status', 20);
            $table->string('recommended_by', 20); 
            $table->unsignedBigInteger('ahp_result_id')->nullable(); 
            $table->unsignedBigInteger('wp_result_id')->nullable(); 
            $table->string('recommendation_reason', 255)->nullable(); 
            $table->boolean('notified')->default(false); 
            $table->timestamp('notified_at')->nullable(); 
            $table->timestamps();
            
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
}; 