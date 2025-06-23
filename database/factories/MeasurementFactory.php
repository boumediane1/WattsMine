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
        $solar_arrays = [
            [
                'title' => "Solar Array 1",
                'active_power' => 1000
            ],
            [
                'title' => "Solar Array 2",
                'active_power' => 900
            ],
            [
                'title' => "Solar Array 3",
                'active_power' => 300
            ],
            [
                'title' => "Solar Array 4",
                'active_power' => 151
            ],
        ];

        $load = [
            [
                'title' => 'Pool & Cave Total',
                'active_power' => 3100
            ],
            [
                'title' => 'Rack UPS',
                'active_power' => 2609
            ]
        ];

        return [
            'utility_grid_active_power' => 24,
            'solar_arrays' => $solar_arrays,
            'consumption' => $load,
            'measured_at' => now()
        ];
    }
}
