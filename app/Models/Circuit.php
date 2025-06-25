<?php

namespace App\Models;

use App\Enums\CircuitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Circuit extends Model
{
    public static function findCircuitByTitle(string $title)
    {
        return Circuit::query()->where('title', $title)->first();
    }

    protected function casts(): array
    {
        return [
            'type' => CircuitType::class
        ];
    }

    public function readings(): HasMany
    {
        return $this->hasMany(Reading::class);
    }
}
