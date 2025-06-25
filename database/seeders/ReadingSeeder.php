<?php

namespace Database\Seeders;

use App\Enums\CircuitType;
use App\Models\Circuit;
use App\Models\Reading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::enableQueryLog();
        $circuits = Circuit::query()->whereIn('type', ['production', 'consumption'])->get();
        $utility_grid_circuit_id = Circuit::findCircuitByTitle('Grid Utility')->id;

        $yesterday = now();
        $readings = [];
    for ($i = 0; $i < 86_400; $i += 5) {
            $measured_at = $yesterday->copy()->addSeconds($i)->toString();

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
        }


        foreach (array_chunk($readings, 1000) as $chunk) {
            Reading::query()->insert($chunk);
        }
    }
}
