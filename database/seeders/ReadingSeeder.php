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
                $this->simulation->fakeData(),
                $measured_at
            ));
        }

        foreach (array_chunk($readings, 1000) as $chunk) {
            Reading::query()->insert($chunk);
        }
    }
}
