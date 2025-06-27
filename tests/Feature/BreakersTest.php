<?php

use App\Enums\CircuitType;
use App\Models\Circuit;
use App\Models\Reading;
use App\Models\User;
use App\Services\SimulationService;

test('test updates circuit status', function () {
    $this->actingAs(User::factory()->create());
    $this->withoutExceptionHandling();

    Circuit::query()->create([
        'title' => 'Circuit',
        'type' => CircuitType::Production,
        'user_id' => 1,
        'on' => true
    ]);

    $this->patch('breakers/1', [
        'on' => false
    ]);

    $circuit = Circuit::query()->first();

    expect($circuit->on)->toBeFalse();
});

test('test readings are ordered by type then by title', function () {
    $this->actingAs(User::factory()->create());

    Circuit::query()->create([
        'type' => CircuitType::Consumption->value,
        'title' => 'Z',
        'user_id' => 1
    ]);

    Circuit::query()->create([
        'type' => CircuitType::Consumption->value,
        'title' => 'A',
        'user_id' => 1
    ]);

    Circuit::query()->create([
        'type' => CircuitType::Production->value,
        'title' => 'Z',
        'user_id' => 1
    ]);

    Circuit::query()->create([
        'type' => CircuitType::Production->value,
        'title' => 'A',
        'user_id' => 1
    ]);

    Reading::query()->insert([
        [
            'circuit_id' => 1,
            'active_power' => 100,
            'measured_at' => '2025-01-01 00:00:00'
        ],
        [
            'circuit_id' => 2,
            'active_power' => 200,
            'measured_at' => '2025-01-01 00:01:00'
        ],
        [
            'circuit_id' => 3,
            'active_power' => 300,
            'measured_at' => '2025-01-01 00:00:00'
        ],
        [
            'circuit_id' => 4,
            'active_power' => 400,
            'measured_at' => '2025-01-01 00:01:00'
        ],
    ]);

    expect(Reading::latest())->toMatchArray([
        [
            'id' => 4,
            'type' => CircuitType::Production->value,
            'title' => 'A',
            'on' => true,
            'active_power' => 400,
            'measured_at' => '2025-01-01 00:01:00'
        ],
        [
            'id' => 3,
            'type' => CircuitType::Production->value,
            'title' => 'Z',
            'on' => true,
            'active_power' => 300,
            'measured_at' => '2025-01-01 00:00:00'
        ],
        [
            'id' => 2,
            'type' => CircuitType::Consumption->value,
            'title' => 'A',
            'on' => true,
            'active_power' => 200,
            'measured_at' => '2025-01-01 00:01:00'
        ],
        [
            'id' => 1,
            'type' => CircuitType::Consumption->value,
            'title' => 'Z',
            'on' => true,
            'active_power' => 100,
            'measured_at' => '2025-01-01 00:00:00'
        ],
    ]);
});

test('test active power is set to zero when circuit is turned off', function () {
    $this->actingAs(User::factory()->create());

    $simulation = app(SimulationService::class);

    Circuit::query()->create([
        'title' => 'Circuit',
        'type' => CircuitType::Production->value,
        'user_id' => 1,
        'on' => false
    ]);

    Circuit::query()->create([
        'title' => 'Utility Grid',
        'type' => CircuitType::UtilityGrid->value,
        'user_id' => 1,
        'on' => true
    ]);

    $now = now();
    $readings = $simulation->readings(['Circuit' => 100], $now);

    expect($readings)->toMatchArray([
        [
            'active_power' => 0,
            'circuit_id' => 1,
            'measured_at' => $now
        ]
    ]);
});
