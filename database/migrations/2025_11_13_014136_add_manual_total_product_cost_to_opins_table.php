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
            $table->decimal('manual_total_product_cost', 15, 2)->nullable()->after('sg_a_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opins', function (Blueprint $table) {
            $table->dropColumn('manual_total_product_cost');
        });
    }
};
