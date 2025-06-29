<?php

use App\Enums\CircuitType;
use App\Models\Circuit;
use App\Models\Reading;
use App\Models\User;
use App\Services\SimulationService;
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

test('confirms that the dashboard accurately displays power production and consumption data by hour', function () {
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

test('ensures that the dashboard shows the correct total power for both production and consumption in real-time', function () {
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

test('verifies that the active power from the utility grid is computed correctly', function () {
    $this->actingAs(User::factory()->create());

    $simulation = app(SimulationService::class);

    seed(CircuitSeeder::class);

    $now = now();
    $readings = $simulation->readings([
        'Solar Array 1' => 100,
        'Solar Array 2' => 200,
        'Living Room TV' => 300,
        'Washing Machine' => 400,
    ], $now);

    expect($readings)->toMatchArray([
        [
            'active_power' => 100,
            'circuit_id' => 1,
            'measured_at' => $now
        ],
        [
            'active_power' => 200,
            'circuit_id' => 2,
            'measured_at' => $now
        ],
        [
            'active_power' => 300,
            'circuit_id' => 6,
            'measured_at' => $now
        ],
        [
            'active_power' => 400,
            'circuit_id' => 7,
            'measured_at' => $now
        ],
        [
            'active_power' => 400,
            'circuit_id' => 10,
            'measured_at' => $now
        ],
    ]);
});

test('ensures that the latest readings from all circuits are retrieved and displayed correctly', function () {
    $this->actingAs(User::factory()->create());

    seed(CircuitSeeder::class);

    Reading::query()->insert([
        [
            'circuit_id' => 1,
            'active_power' => 100,
            'measured_at' => '2025-01-01 00:00:00'
        ],
        [
            'circuit_id' => 1,
            'active_power' => 200,
            'measured_at' => '2025-01-01 00:01:00'
        ],
        [
            'circuit_id' => 5,
            'active_power' => 300,
            'measured_at' => '2025-01-01 00:00:00'
        ],
        [
            'circuit_id' => 5,
            'active_power' => 400,
            'measured_at' => '2025-01-01 00:01:00'
        ],
    ]);

    expect(Reading::latest())->toMatchArray([
        [
            'id' => 1,
            'title' => 'Solar Array 1',
            'type' => 'production',
            'active_power' => 200,
            'measured_at' => '2025-01-01 00:01:00',
            'on' => true
        ],
        [
            'id' => 5,
            'title' => 'Refrigerator',
            'type' => 'consumption',
            'active_power' => 400,
            'measured_at' => '2025-01-01 00:01:00',
            'on' => true
        ],
    ]);
});
