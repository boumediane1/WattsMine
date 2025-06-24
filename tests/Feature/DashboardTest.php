<?php

use App\Models\Circuit;
use App\Models\Reading;
use App\Models\User;
use Database\Seeders\CircuitSeeder;
use Inertia\Testing\AssertableInertia;
use function Pest\Laravel\seed;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dashboard')->assertOk();
});

test('calculates real time total active power from solar arrays', function () {
});

test('calculates real time total consumption', function () {
});

test('retrieves real time utility grid active power', function () {
});

test('test dashboard shows correct power production by hour', function () {
    $this->actingAs(User::factory()->create());
    $this->withoutExceptionHandling();

    seed(CircuitSeeder::class);

    Reading::query()->create([
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 1')->id,
        'active_power' => 1000,
        'measured_at' => '2025-01-01 01:00:00'
    ]);

    Reading::query()->create([
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 2')->id,
        'active_power' => 2000,
        'measured_at' => '2025-01-01 01:00:05'
    ]);

    Reading::query()->create([
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 3')->id,
        'active_power' => 1000,
        'measured_at' => '2025-01-01 02:00:00'
    ]);

    Reading::query()->create([
        'circuit_id' => Circuit::findCircuitByTitle('Washing Machine')->id,
        'active_power' => 3000,
        'measured_at' => '2025-01-01 00:00:10'
    ]);


    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')
        ->where('data', [
            'production' => [
                [
                    'hour' => 1,
                    'active_power' => 1500
                ],
                [
                    'hour' => 2,
                    'active_power' => 1000
                ],
            ],
            'consumption' => [
                [
                    'hour' => 0,
                    'active_power' => 3000
                ]
            ]
        ])
    );
});
