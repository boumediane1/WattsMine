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
            if ($circuit->type === CircuitType::Consumption) {
                $active_power_utility_grid += $data[$circuit->title];
            } else if ($circuit->type === CircuitType::Production) {
                $active_power_utility_grid -= $data[$circuit->title];
            }

            $reading = [
                'active_power' => $data[$circuit->title],
                'circuit_id' => $circuit->id,
                'measured_at' => $measured_at
            ];

            $readings[] = $reading;
        }

        $readings[] = [
            'active_power' => $active_power_utility_grid,
            'circuit_id' => Circuit::findCircuitByTitle('Grid Utility')->id,
            'measured_at' => $measured_at
        ];

        return $readings;
    }
}
