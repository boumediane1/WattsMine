<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Measurement>
 */
class MeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active_power_production' => fake()->numberBetween(3000, 5000),
            'active_power_grid' => fake()->numberBetween(3000, 5000),
            'active_power_load' => fake()->numberBetween(3000, 5000),
            'active_power_battery' => fake()->numberBetween(3000, 5000),
        ];
    }
}
