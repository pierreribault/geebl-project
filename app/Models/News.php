<?php

namespace App\Models;

use App\Models\Seller;
use App\Enums\NewsStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'slug',
        'status',
        'seller_id'
    ];

    protected $casts = [
        'status' => NewsStatus::class,
        'date' => 'date:Y-m-d',
    ];

    public function seller(): BelongsToMany
    {
        return $this->belongsToMany(Seller::class);
    }
}
