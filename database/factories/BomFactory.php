<?php

namespace Database\Factories;

use App\Models\Component;
use App\Models\Opin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bom>
 */
class BomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'opin_id' => Opin::factory(),
            'component_id' => Component::factory(),
            'quantity' => $this->faker->randomFloat(2, 0.1, 100.0),
        ];
    }
}
