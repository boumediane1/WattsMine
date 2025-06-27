<?php

use App\Events\ReadingsSimulated;
use App\Models\Reading;
use App\Services\SimulationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $simulation = app(SimulationService::class);
    $readings = $simulation->readings($simulation->fakeData(), now());
    Reading::query()->insert($readings);
    ReadingsSimulated::dispatch(Reading::latest());
})->everySecond();
