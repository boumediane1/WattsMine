<?php

use App\Enums\CircuitType;
use App\Models\User;
use Database\Seeders\CircuitSeeder;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\seed;

test('test breakers page shows circuits excluding grid utility', function () {
    $this->actingAs(User::factory()->create());

    seed(CircuitSeeder::class);

    $this->get('/breakers')->assertInertia(fn(AssertableInertia $page) =>
        $page->component('breakers')
        ->where('circuits', [
            [
                'id' => 1,
                'title' => "Solar Array 1",
                'type' => CircuitType::Production->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 2,
                'title' => "Solar Array 2",
                'type' => CircuitType::Production->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 3,
                'title' => "Solar Array 3",
                'type' => CircuitType::Production->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 4,
                'title' => "Solar Array 4",
                'type' => CircuitType::Production->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 5,
                'title' => 'Refrigerator',
                'type' => CircuitType::Consumption->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 6,
                'title' => 'Living Room TV',
                'type' => CircuitType::Consumption->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 7,
                'title' => 'Washing Machine',
                'type' => CircuitType::Consumption->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 8,
                'title' => 'Microwave Oven',
                'type' => CircuitType::Consumption->value,
                'on' => true,
                'user_id' => 1,
            ],
            [
                'id' => 9,
                'title' => 'Wi-Fi & Devices',
                'type' => CircuitType::Consumption->value,
                'on' => true,
                'user_id' => 1,
            ],
        ])
    );
});
