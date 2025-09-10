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
        Schema::create('opins', function (Blueprint $table) {
            $table->id();
            $table->string('part_no');
            $table->string('part_name');
            $table->decimal('sales_price', 10, 2);
            $table->decimal('rm_cost', 10, 2);
            $table->decimal('ckd_cost', 10, 2);
            $table->decimal('ip_cost', 10, 2);
            $table->decimal('lp_cost', 10, 2);
            $table->decimal('labor_cost', 10, 2);
            $table->decimal('machine_cost', 10, 2);
            $table->decimal('current_machine', 10, 2);
            $table->decimal('other_fixed', 10, 2);
            $table->decimal('defect_cost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opins');
    }
};
