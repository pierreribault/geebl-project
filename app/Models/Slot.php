<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Slot extends Pivot
{
    protected $fillable = [
        'transaction',
        'quantity',
        'status',
    ];
}
