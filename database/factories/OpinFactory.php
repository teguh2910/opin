<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Opin>
 */
class OpinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'part_no' => 'PART-'.$this->faker->unique()->numberBetween(1000, 9999),
            'part_name' => $this->faker->words(2, true),
            'sales_price' => $this->faker->randomFloat(2, 50, 500),
            'labor_cost' => $this->faker->randomFloat(2, 5, 30),
            'machine_cost' => $this->faker->randomFloat(2, 10, 80),
            'current_machine' => $this->faker->randomFloat(2, 5, 40),
            'other_fixed' => $this->faker->randomFloat(2, 1, 10),
            'defect_cost' => $this->faker->randomFloat(2, 0, 5),
        ];
    }
}
