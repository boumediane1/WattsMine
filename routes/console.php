<?php

use App\Models\Circuit;
use App\Models\Reading;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    foreach (Circuit::all() as $circuit) {
        $active_power = match ($circuit->title) {
            'Solar Array 1', 'Solar Array 2' => fake()->numberBetween(900, 1000),
            'Solar Array 3', 'Solar Array 4' => fake()->numberBetween(20, 80),
            'Refrigerator', 'Wi-Fi & Devices' => fake()->numberBetween(100, 200),
            'Living Room TV' => fake()->numberBetween(80, 90),
            'Washing Machine' => fake()->numberBetween(600, 700),
            'Microwave Oven' => fake()->numberBetween(1100, 1200),
        };

        Reading::query()->create([
            'active_power' => $active_power,
            'circuit_id' => $circuit->id,
            'measured_at' => now()
        ]);
    }
})->everySecond();
