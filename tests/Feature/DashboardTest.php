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

test('test dashboard shows correct power production and consumption by hour', function () {
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

test('test dashboard shows total power for production and consumption in real time', function () {
    $this->actingAs(User::factory()->create());
    seed(CircuitSeeder::class);
    $this->withoutExceptionHandling();

    Reading::query()->create([
        'active_power' => 100,
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 1')->id,
        'measured_at' => '2025-01-01 00:00:00'
    ]);

    Reading::query()->create([
        'active_power' => 200,
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 2')->id,
        'measured_at' => '2025-01-01 00:00:00'
    ]);

    Reading::query()->create([
        'active_power' => 300,
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 1')->id,
        'measured_at' => '2025-01-01 01:00:00'
    ]);


    Reading::query()->create([
        'active_power' => 400,
        'circuit_id' => Circuit::findCircuitByTitle('Solar Array 2')->id,
        'measured_at' => '2025-01-01 01:00:00'
    ]);

    Reading::query()->create([
        'active_power' => 500,
        'circuit_id' => Circuit::findCircuitByTitle('Washing Machine')->id,
        'measured_at' => '2025-01-01 00:00:00'
    ]);

    Reading::query()->create([
        'active_power' => 600,
        'circuit_id' => Circuit::findCircuitByTitle('Washing Machine')->id,
        'measured_at' => '2025-01-01 00:00:00'
    ]);

    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')
        ->where('production', 700)
        ->where('consumption', 600)
    );
});
