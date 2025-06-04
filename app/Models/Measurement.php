<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'solar_arrays' => 'array'
        ];
    }

}
