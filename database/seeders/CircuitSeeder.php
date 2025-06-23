<?php

namespace Database\Seeders;

use App\Enums\CircuitType;
use App\Models\Circuit;
use Illuminate\Database\Seeder;

class CircuitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $production_circuits = [];

        for ($i = 1; $i <= 4; $i++) {
            $production_circuits[] = [
                'title' => 'Solar Array 1',
                'type' => CircuitType::Production->name,
                'on' => true,
                'user_id' => 1,
            ];
        }

        Circuit::query()->insert($production_circuits);
    }
}
