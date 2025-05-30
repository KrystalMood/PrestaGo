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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description');
            $table->string('organizer', 255);
            $table->enum('level', ['international', 'national', 'regional', 'provincial', 'university']);
            $table->enum('type', ['individual', 'team', 'both']);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('registration_start');
            $table->date('registration_end');
            $table->date('competition_date');
            $table->string('registration_link', 255)->nullable();
            $table->string('status', 20);
            $table->boolean('verified')->default(false);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
            
            $table->foreign('added_by')->references('id')->on('users');
            $table->foreign('period_id')->references('id')->on('periods');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
}; 