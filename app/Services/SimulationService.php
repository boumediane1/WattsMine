<?php


namespace App\Services;

use App\Enums\CircuitType;
use App\Models\Circuit;
use Carbon\Carbon;

class SimulationService
{
    public function readings($data, Carbon $measured_at): array
    {
        $circuits = Circuit::query()
            ->whereIn('title', array_keys($data))
            ->get();

        $active_power_utility_grid = 0;

        foreach ($circuits as $circuit) {
            $active_power = $circuit->on ? $data[$circuit->title] : 0;

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
            'circuit_id' => Circuit::findCircuitByTitle('Utility Grid')->id,
            'measured_at' => $measured_at
        ];

        return $readings;
    }

    public function fakeData(): array
    {
        return [
            'Solar Array 1' => fake()->numberBetween(900, 1000),
            'Solar Array 2' => fake()->numberBetween(900, 1000),
            'Solar Array 3' => fake()->numberBetween(20, 80),
            'Solar Array 4' => fake()->numberBetween(20, 80),
            'Refrigerator' => fake()->numberBetween(100, 200),
            'Wi-Fi & Devices' => fake()->numberBetween(100, 200),
            'Living Room TV' => fake()->numberBetween(80, 90),
            'Washing Machine' => fake()->numberBetween(600, 700),
            'Microwave Oven' => fake()->numberBetween(1100, 1200)
        ];
    }
}
