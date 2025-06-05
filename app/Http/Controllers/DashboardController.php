<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $data = Measurement::all()->map(function ($measurement) {
            return [
                'date' => $measurement->measured_at,
                'production' => collect($measurement->solar_arrays)->sum('active_power'),
                'consumption' => collect($measurement->consumption)->sum('active_power'),
            ];
        });

        return Inertia::render('dashboard', [
            'production' => Measurement::production(),
            'consumption' => Measurement::consumption(),
            'grid_utility' => Measurement::gridUtility(),
            'data' => $data
        ]);
    }
}
