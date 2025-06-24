<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reading extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function circuit(): BelongsTo
    {
        return $this->belongsTo(Reading::class);
    }
}
