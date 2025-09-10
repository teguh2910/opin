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
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opin_id')->constrained('opins')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
            $table->decimal('quantity', 10, 3)->default(1); // quantity of component used in finished good
            $table->timestamps();

            // Ensure no duplicate component per finished good
            $table->unique(['opin_id', 'component_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_materials');
    }
};
