<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $now = now();
        $startTime = $now->copy()->subDay();

        $results = DB::table('measurements')
            ->whereBetween('measured_at', [$startTime, $now])
            ->select([
                DB::raw("date_trunc('hour', measured_at) as hour"),
                DB::raw("avg(utility_grid_active_power) as utility_grid_active_power"),
                DB::raw("avg(
            (select sum((elem->>'active_power')::float)
             from jsonb_array_elements(solar_arrays) as elem)
        ) as production"),
                DB::raw("avg(
            (select sum((elem->>'active_power')::float)
             from jsonb_array_elements(consumption) as elem)
        ) as consumption")
            ])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => $item->hour,
                    'production' => (float)$item->production,
                    'consumption' => (float)$item->consumption,
                    'utility_grid_active_power' => (float)$item->utility_grid_active_power,
                ];
            });

        return Inertia::render('dashboard', [
            'production' => Measurement::production(),
            'consumption' => Measurement::consumption(),
            'grid_utility' => Measurement::gridUtility(),
            'data' => $results
        ]);
    }
}
