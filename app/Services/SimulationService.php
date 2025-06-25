<?php


namespace App\Services;

use App\Enums\CircuitType;
use App\Models\Circuit;
use Carbon\Carbon;

class SimulationService
{
    public function readings(Carbon $measured_at): array
    {
        $circuits = Circuit::query()->whereIn('type', ['production', 'consumption'])->get();
        $utility_grid_circuit_id = Circuit::findCircuitByTitle('Grid Utility')->id;

        $active_power_utility_grid = 0;

        foreach ($circuits as $circuit) {
            $active_power = match ($circuit->title) {
                'Solar Array 1', 'Solar Array 2' => fake()->numberBetween(900, 1000),
                'Solar Array 3', 'Solar Array 4' => fake()->numberBetween(20, 80),
                'Refrigerator', 'Wi-Fi & Devices' => fake()->numberBetween(100, 200),
                'Living Room TV' => fake()->numberBetween(80, 90),
                'Washing Machine' => fake()->numberBetween(600, 700),
                'Microwave Oven' => fake()->numberBetween(1100, 1200),
            };

            if ($circuit->type === CircuitType::Consumption) {
                $active_power_utility_grid += $active_power;
            } else if ($circuit->type === CircuitType::Production) {
                $active_power_utility_grid -= $active_power;
            }

            $reading = [
                'active_power' => $active_power,
                'circuit_id' => $circuit->id,
                'measured_at' => $measured_at
            ];

            $readings[] = $reading;
        }

        $readings[] = [
            'active_power' => $active_power_utility_grid,
            'circuit_id' => $utility_grid_circuit_id,
            'measured_at' => $measured_at
        ];

        return $readings;
    }
}
