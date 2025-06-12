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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('icon_type')->comment('Type of icon to display');
            $table->text('message')->comment('Activity message text');
            $table->unsignedBigInteger('user_id')->nullable()->comment('User who performed the activity');
            $table->string('causer')->nullable()->comment('Who or what caused this activity');
            $table->string('subject')->nullable()->comment('Subject of the activity');
            $table->string('action')->comment('Action performed');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
