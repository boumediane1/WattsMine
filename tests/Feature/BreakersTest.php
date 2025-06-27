<?php

use App\Models\User;
use Database\Seeders\CircuitSeeder;
use function Pest\Laravel\seed;

test('test turning circuits on/off', function () {
    $this->actingAs(User::factory()->create());

    seed(CircuitSeeder::class);
});
