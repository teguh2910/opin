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
            $table->dropColumn(['rm_cost', 'ckd_cost', 'ip_cost', 'lp_cost']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opins', function (Blueprint $table) {
            $table->decimal('rm_cost', 10, 2)->default(0);
            $table->decimal('ckd_cost', 10, 2)->default(0);
            $table->decimal('ip_cost', 10, 2)->default(0);
            $table->decimal('lp_cost', 10, 2)->default(0);
        });
    }
};
