<?php

namespace App\Models;

use App\Enums\ProductStatus;
use App\Models\User;
use App\Models\Invoice;
use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use UuidPrimaryKey;
    use InteractsWithMedia;

    const minimumBeforeLowStock = 20;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
    ];

    protected $appends = [
        'badge',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getBadgeAttribute(): string
    {
        return ProductStatus::available($this->quantity);
    }
}
