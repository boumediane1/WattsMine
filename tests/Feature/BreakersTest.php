<?php

use App\Enums\CircuitType;
use App\Models\Circuit;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

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
    ])->assertInertia(fn(AssertableInertia $page) => $page
        ->component('breakers', [
            'success' => true
        ])
    );

    $circuit = Circuit::query()->first();

    expect($circuit->on)->toBeFalse();
});
