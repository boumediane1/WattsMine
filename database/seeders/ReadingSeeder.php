<?php

namespace Database\Seeders;

use App\Enums\CircuitType;
use App\Models\Circuit;
use App\Models\Reading;
use Illuminate\Database\Seeder;

class ReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $circuits = Circuit::query()->where('type', CircuitType::Production->name)->get();

        $yesterday = now();

        $readings = [];
        for ($i = 0; $i < 86_400; $i += 5) {
            $circuits->each(function ($circuit) use  ($i, $yesterday, &$readings) {
                $reading = [
                    'active_power' => fake()->numberBetween(2000, 2500),
                    'circuit_id' => $circuit->id,
                    'measured_at' => $yesterday->copy()->addSeconds($i)->toString()
                ];

                $readings[] = $reading;
            })->toArray();
        }

        foreach (array_chunk($readings, 5000) as $chunk) {
            Reading::query()->insert($chunk);
        }

//        $queries = DB::getQueryLog();
    }
}
