<?php

namespace App\Http\Controllers;

use App\Enums\CircuitType;
use App\Models\Circuit;
use Illuminate\Http\Request;
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

    public function update(Circuit $circuit, Request $request)
    {
        $success = $circuit->update([
            'on' => $request->input('on')
        ]);

        return Inertia::render('breakers', [
            ['success' => $success]
        ]);
    }
}
