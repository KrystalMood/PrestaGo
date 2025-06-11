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
        Schema::table('recommendations', function (Blueprint $table) {
            if (!Schema::hasColumn('recommendations', 'calculation_method')) {
                $table->string('calculation_method')->nullable()->comment('Method used for calculation: ahp, wp, or hybrid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            if (Schema::hasColumn('recommendations', 'calculation_method')) {
                $table->dropColumn('calculation_method');
            }
        });
    }
}; 