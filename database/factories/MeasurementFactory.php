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
        $solar_arrays = array_map(fn($i) => [
            'title' => "Solar Array $i",
            'active_power' => 12
        ], range(1, 4));

        $load = [
            [
                'title' => 'Pool & Cave Total',
                'active_power' => 1612
            ],
            [
                'title' => 'Rack UPS',
                'active_power' => 674
            ]
        ];

        return [
            'utility_grid_active_power' => 2221,
            'solar_arrays' => json_encode($solar_arrays),
            'consumption' => json_encode($load),
            'measured_at' => now()
        ];
    }
}
