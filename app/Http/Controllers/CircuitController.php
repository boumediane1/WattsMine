<?php

namespace App\Http\Controllers;

use App\Enums\CircuitType;
use App\Models\Circuit;
use Inertia\Inertia;

class CircuitController extends Controller
{
    public function index()
    {
        $circuits = Circuit::query()->whereNot('type', CircuitType::UtilityGrid)->get();

        return Inertia::render('breakers', [
            'circuits' => $circuits
        ]);
    }
}
