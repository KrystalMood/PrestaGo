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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id('achievement_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description');
            $table->string('competition_name');
            $table->unsignedBigInteger('competition_id')->nullable();
            $table->string('type');
            $table->string('level');
            $table->date('date');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->unsignedBigInteger('period_id')->nullable();
            $table->timestamps();
            
            // Foreign Key
            $table->foreign('user_id')->references('users_id')->on('users');
            $table->foreign('verified_by')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
}; 