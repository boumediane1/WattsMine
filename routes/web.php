<?php

use App\Http\Controllers\CircuitController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('monitoring', function () {
        return Inertia::render('monitoring');
    });

    Route::get('breakers', [CircuitController::class, 'index'])->name('breakers.index');

    Route::patch('breakers/{circuit}', [CircuitController::class, 'update']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

