<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reading extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function circuit(): BelongsTo
    {
        return $this->belongsTo(Reading::class);
    }

    public static function latest()
    {
        return Circuit::query()
            ->with('readings', function (HasMany $readings) {
                $readings
                    ->latest('id')
                    ->take(1);
            })
            ->orderBy('type', 'desc')
            ->orderBy('title')
            ->get()
            ->filter(fn($item) => count($item->readings) > 0)
            ->values()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => $item->type->value,
                    'active_power' => $item->readings[0]->active_power,
                    'measured_at' => $item->readings[0]->measured_at,
                    'on' => $item->on
                ];
            });
    }
}
