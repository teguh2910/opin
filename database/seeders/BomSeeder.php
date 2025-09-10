<?php

namespace Database\Seeders;

use App\Models\Bom;
use App\Models\Component;
use App\Models\Opin;
use Illuminate\Database\Seeder;

class BomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opins = Opin::all();
        $components = Component::all();

        foreach ($opins as $opin) {
            // Create BOM entries for each component type with random quantities
            foreach ($components as $component) {
                Bom::create([
                    'opin_id' => $opin->id,
                    'component_id' => $component->id,
                    'quantity' => fake()->randomFloat(2, 0.1, 10), // Random quantity between 0.1 and 10
                ]);
            }
        }
    }
}
