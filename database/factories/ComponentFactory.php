<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Component>
 */
class ComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'part_no' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'part_name' => $this->faker->words(2, true),
            'type' => $this->faker->randomElement(['rm', 'lp', 'ip', 'ckd']),
            'unit_cost' => $this->faker->randomFloat(2, 1.00, 999.99),
            'unit' => $this->faker->randomElement(['pcs', 'kg', 'm', 'l', 'box']),
        ];
    }
}
