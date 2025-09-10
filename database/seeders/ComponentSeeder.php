<?php

namespace Database\Seeders;

use App\Models\Component;
use Illuminate\Database\Seeder;

class ComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $components = [
            [
                'part_no' => 'RM',
                'part_name' => 'Raw Material',
                'type' => 'rm',
                'unit_cost' => 50000,
                'unit' => 'kg',
            ],
            [
                'part_no' => 'CKD',
                'part_name' => 'CKD Cost',
                'type' => 'ckd',
                'unit_cost' => 75000,
                'unit' => 'pcs',
            ],
            [
                'part_no' => 'IP',
                'part_name' => 'IP Cost',
                'type' => 'ip',
                'unit_cost' => 25000,
                'unit' => 'pcs',
            ],
            [
                'part_no' => 'LP',
                'part_name' => 'LP Cost',
                'type' => 'lp',
                'unit_cost' => 15000,
                'unit' => 'pcs',
            ],
        ];

        foreach ($components as $component) {
            Component::updateOrCreate(
                ['part_no' => $component['part_no']],
                $component
            );
        }
    }
}
