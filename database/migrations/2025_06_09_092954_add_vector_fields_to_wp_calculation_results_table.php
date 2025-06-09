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
        Schema::table('wp_calculation_results', function (Blueprint $table) {
            $table->float('vector_s', 10, 6)->nullable()->after('final_score');
            $table->float('vector_v', 10, 6)->nullable()->after('vector_s');
            $table->float('relative_preference', 10, 6)->nullable()->after('vector_v');
            $table->integer('rank')->nullable()->after('relative_preference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wp_calculation_results', function (Blueprint $table) {
            $table->dropColumn('vector_s');
            $table->dropColumn('vector_v');
            $table->dropColumn('relative_preference');
            $table->dropColumn('rank');
        });
    }
};
