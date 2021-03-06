<?php

namespace App\Models;

use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'status',
        'redactor_id',
        'content',
    ];

    protected $casts = [
        'status' => NewsStatus::class,
        'date' => 'date:Y-m-d',
    ];

    public function redactor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', NewsStatus::Published);
    }
}
