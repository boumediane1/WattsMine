<?php

namespace Database\Seeders;

use App\Models\Measurement;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Measurement::query()->delete();

        $measurements = [];

        $yesterday = now()->subDay();

        for ($i = 0; $i < 86_400; $i += 5) {
            $measurement = Measurement::factory()->make([
                'measured_at' => $yesterday->copy()->addSeconds($i)
            ])->getAttributes();
            $measurements[] = $measurement;
        }

        foreach (array_chunk($measurements, 1000) as $chunk) {
            Measurement::query()->insert($chunk);
        }
    }
}
