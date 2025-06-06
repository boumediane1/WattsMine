<?php

use App\Models\Measurement;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    Measurement::factory()->create();

    $this->get('/dashboard')->assertOk();
});

test('calculates real time total active power from solar arrays', function () {
    $this->actingAs(User::factory()->create());

    Measurement::factory()->create([
        'solar_arrays' => [
            ['title' => 'Solar Array 1', 'active_power' => 10],
            ['title' => 'Solar Array 2', 'active_power' => 20],
        ],
        'measured_at' => now()->subHour()
    ]);

    Measurement::factory()->create([
        'solar_arrays' => [
            ['title' => 'Solar Array 1', 'active_power' => 30],
            ['title' => 'Solar Array 2', 'active_power' => 40],
        ],
        'measured_at' => now()
    ]);

    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')->where('production', 70));
});

test('calculates real time total consumption', function () {
    $this->actingAs(User::factory()->create());

    Measurement::factory()->create([
        'consumption' => [
            ['title' => 'Television', 'active_power' => 10],
            ['title' => 'Washing Machine', 'active_power' => 20],
        ],
        'measured_at' => now()->subHour()
    ]);

    Measurement::factory()->create([
        'consumption' => [
            ['title' => 'Solar Array 1', 'active_power' => 30],
            ['title' => 'Solar Array 2', 'active_power' => 40],
        ],
        'measured_at' => now()
    ]);

    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')->where('consumption', 70));
});

test('retrieves real time utility grid active power', function () {
    $this->actingAs(User::factory()->create());

    Measurement::factory()->create([
        'utility_grid_active_power' => 10,
        'measured_at' => now()->subHour()
    ]);

    Measurement::factory()->create([
        'utility_grid_active_power' => 20,
        'measured_at' => now()
    ]);

    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')->where('grid_utility', 20));
});

test('should correctly aggregate solar production and consumption', function () {
    $this->actingAs(User::factory()->create());

    $this->withoutExceptionHandling();
    $now = now();

    Measurement::factory()->create([
        'solar_arrays' => [
            ['title' => 'Solar Array 1', 'active_power' => 1],
            ['title' => 'Solar Array 2', 'active_power' => 2],
        ],
        'consumption' => [
            ['title' => 'Television', 'active_power' => 3],
            ['title' => 'Washing Machine', 'active_power' => 4],
        ],
        'utility_grid_active_power' => 100,
        'measured_at' => '2025-06-04 22:00:00'
    ]);

    Measurement::factory()->create([
        'solar_arrays' => [
            ['title' => 'Solar Array 1', 'active_power' => 5],
            ['title' => 'Solar Array 2', 'active_power' => 6],
        ],
        'consumption' => [
            ['title' => 'Solar Array 1', 'active_power' => 7],
            ['title' => 'Solar Array 2', 'active_power' => 8],
        ],
        'utility_grid_active_power' => 200,
        'measured_at' => '2025-06-04 22:00:01',
    ]);

    Measurement::factory()->create([
        'solar_arrays' => [
            ['title' => 'Solar Array 1', 'active_power' => 9],
            ['title' => 'Solar Array 2', 'active_power' => 10],
        ],
        'consumption' => [
            ['title' => 'Solar Array 1', 'active_power' => 11],
            ['title' => 'Solar Array 2', 'active_power' => 12],
        ],
        'utility_grid_active_power' => 300,
        'measured_at' => '2025-06-04 23:00:00'
    ]);

    $this->get('/dashboard')->assertInertia(fn(AssertableInertia $page) => $page
        ->component('dashboard')->where('data', [
            [
                'date' => '2025-06-04 22:00:00',
                'production' => 7,
                'consumption' => 11,
                'utility_grid_active_power' => 150,
            ],
            [
                'date' => '2025-06-04 23:00:00',
                'production' => 19,
                'consumption' => 23,
                'utility_grid_active_power' => 300,
            ],
        ])
    );
});
