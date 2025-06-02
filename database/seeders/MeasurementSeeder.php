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

        for ($i = 0; $i < 86_400; $i += 10) {
            $measurement = Measurement::factory()->make([
                'measured_at' => $yesterday->clone()->addSeconds($i)
            ])->getAttributes();

            $measurements[] = $measurement;
        }

        DB::table('measurements')->insert($measurements);
    }
}
