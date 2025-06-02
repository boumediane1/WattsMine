<?php

namespace Database\Seeders;

use App\Models\Measurement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $measurements = [];

        $yesterday = now()->addDays(-1);

        for ($i = 0; $i < 86_400; $i += 5) {
            $measurement = [
                'active_power_production' => fake()->numberBetween(3000, 5000),
                'active_power_grid' => fake()->numberBetween(3000, 5000),
                'active_power_load' => fake()->numberBetween(3000, 5000),
                'active_power_battery' => fake()->numberBetween(3000, 5000),
                'measured_at' => $yesterday->clone()->addSeconds($i)
            ];

            $measurements[] = $measurement;
        }

        foreach (array_chunk($measurements, 1000) as $chunk) {
            Measurement::query()->insert($chunk);
        }
    }
}
