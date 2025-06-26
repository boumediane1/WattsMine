<?php

namespace App\Http\Controllers;

use App\Models\Circuit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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


        $realtime = Circuit::query()->with([
            'readings' => function ($readings) {
                $readings->latest('id')->take(1);
            }])
            ->get()
            ->groupBy('type')
            ->map(fn($item, $key) => $item->sum('readings.0.active_power'));

        return Inertia::render('dashboard', ['data' => $readings, ...$realtime]);
    }
}
