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
                'title' => "Solar Array $i",
                'type' => CircuitType::Production->value,
                'user_id' => 1,
            ];
        }

        $consumption_circuits = [
            [
                'title' => 'Refrigerator',
                'type' => CircuitType::Consumption->value,
                'user_id' => 1,
            ],

            [
                'title' => 'Living Room TV',
                'type' => CircuitType::Consumption->value,
                'user_id' => 1,
            ],
            [
                'title' => 'Washing Machine',
                'type' => CircuitType::Consumption->value,
                'user_id' => 1,
            ],
            [
                'title' => 'Microwave Oven',
                'type' => CircuitType::Consumption->value,
                'user_id' => 1,
            ],
            [
                'title' => 'Wi-Fi & Devices',
                'type' => CircuitType::Consumption->value,
                'user_id' => 1,
            ],
        ];

        $circuits = array_merge($production_circuits, $consumption_circuits);
        Circuit::query()->insert($circuits);
    }
}
