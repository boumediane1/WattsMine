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
        $circuits = Circuit::all();

        $yesterday = now();
        $readings = [];
        for ($i = 0; $i < 86_400; $i += 5) {
            foreach ($circuits as $circuit) {

                $active_power = match ($circuit->title) {
                    'Solar Array 1' => fake()->numberBetween(900, 1000),
                    'Solar Array 2' => fake()->numberBetween(800, 900),
                    'Solar Array 3' => fake()->numberBetween(70, 80),
                    'Solar Array 4' => fake()->numberBetween(20, 30),
                    'Refrigerator', 'Wi-Fi & Devices' => fake()->numberBetween(100, 200),
                    'Living Room TV' => fake()->numberBetween(80, 90),
                    'Washing Machine' => fake()->numberBetween(600, 700),
                    'Microwave Oven' => fake()->numberBetween(1100, 1200),
                };

                $reading = [
                    'active_power' => $active_power,
                    'circuit_id' => $circuit->id,
                    'measured_at' => $yesterday->copy()->addSeconds($i)->toString()
                ];

                $readings[] = $reading;
            }
        }


        DB::enableQueryLog();
        $queries = DB::getQueryLog();
        foreach (array_chunk($readings, 2000) as $chunk) {
            Reading::query()->insert($chunk);
        }
        dd($queries);
    }
}
