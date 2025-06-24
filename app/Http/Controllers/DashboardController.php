<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $readings = DB::table('readings')
            ->leftJoin('circuits', 'circuits.id', '=', 'readings.circuit_id')
            ->get()
            ->groupBy('type')
            ->map(fn($readings) => $readings
                ->groupBy(fn($item) => Carbon::parse($item->measured_at)->hour)
                ->map(fn($item, $key) => [
                    'hour' => $key,
                    'active_power' => round($item->average('active_power'))
                ])
                ->values()
            );

        return Inertia::render('dashboard', ['data' => $readings]);
    }
}
