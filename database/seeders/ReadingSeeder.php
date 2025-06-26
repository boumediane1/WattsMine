<?php

namespace Database\Seeders;

use App\Models\Reading;
use App\Services\SimulationService;
use Illuminate\Database\Seeder;

class ReadingSeeder extends Seeder
{
    public function __construct(private readonly SimulationService $simulation) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $yesterday = now()->subDay();
        $readings = [];

        for ($i = 0; $i < 86_400; $i += 60) {
            $measured_at = $yesterday->copy()->addSeconds(60);
            $readings = array_merge($readings, $this->simulation->readings(
                [
                    'Solar Array 1' => fake()->numberBetween(900, 1000),
                    'Solar Array 2' => fake()->numberBetween(900, 1000),
                    'Solar Array 3' => fake()->numberBetween(20, 80),
                    'Solar Array 4' => fake()->numberBetween(20, 80),
                    'Refrigerator' => fake()->numberBetween(100, 200),
                    'Wi-Fi & Devices' => fake()->numberBetween(100, 200),
                    'Living Room TV' => fake()->numberBetween(80, 90),
                    'Washing Machine' => fake()->numberBetween(600, 700),
                    'Microwave Oven' => fake()->numberBetween(1100, 1200)
                ],
                $measured_at
            ));
        }

        foreach (array_chunk($readings, 1000) as $chunk) {
            Reading::query()->insert($chunk);
        }
    }
}
