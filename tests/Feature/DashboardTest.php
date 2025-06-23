<?php

use App\Models\User;
use Database\Seeders\CircuitSeeder;
use Database\Seeders\ReadingSeeder;
use function Pest\Laravel\seed;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dashboard')->assertOk();
});

test('calculates real time total active power from solar arrays', function () {
    $this->actingAs(User::factory()->create());
    seed([CircuitSeeder::class, ReadingSeeder::class]);
});

test('calculates real time total consumption', function () {
});

test('retrieves real time utility grid active power', function () {
});

test('should correctly aggregate solar production and consumption', function () {
});
