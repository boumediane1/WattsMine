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
        $measurements = [];

        $yesterday = now()->addDays(-1);

        for ($i = 0; $i < 86_400; $i += 1) {
            $solar_arrays = [];

            for ($j = 1; $j <= 4; $j++) {
                $solar_array = [
                    'title' => "Solar Array $j",
                    'active_power' => 12
                ];
                $solar_arrays[] = $solar_array;
            }

            $load = [
                [
                    'title' => 'Pool & Cave Total',
                    'active_power' => 1612
                ],
                [
                    'title' => 'Rack UPS',
                    'active_power' => 674
                ]
            ];

            $measurement = [
                'utility_grid_active_power' => 2221,
                'solar_arrays' => json_encode($solar_arrays),
                'consumption' => json_encode($load),
                'measured_at' => $yesterday->clone()->addSeconds($i)
            ];

            $measurements[] = $measurement;
        }

        foreach (array_chunk($measurements, 1000) as $chunk) {
            Measurement::query()->insert($chunk);
        }
    }
}
