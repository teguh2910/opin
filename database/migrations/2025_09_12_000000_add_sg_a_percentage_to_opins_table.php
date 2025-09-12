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
        Schema::table('opins', function (Blueprint $table) {
            $table->decimal('sg_a_percentage', 5, 4)->default(0.0655)->after('defect_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opins', function (Blueprint $table) {
            $table->dropColumn('sg_a_percentage');
        });
    }
};
