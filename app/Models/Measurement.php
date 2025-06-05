<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'solar_arrays' => 'array',
            'consumption' => 'array'
        ];
    }

    public static function production(): int
    {
        $measurement = Measurement::query()->latest('measured_at')->first();
        return collect($measurement->solar_arrays)->sum('active_power');
    }

    public static function consumption(): int
    {
        $measurement = Measurement::query()->latest('measured_at')->first();
        return collect($measurement->consumption)->sum('active_power');
    }

    public static function gridUtility(): int
    {
        $measurement = Measurement::query()->latest('measured_at')->first();
        return $measurement->utility_grid_active_power;
    }
}
