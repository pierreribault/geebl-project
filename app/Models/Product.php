<?php

namespace App\Models;

use App\Data\ProductData;
use App\Enums\ProductStatus;
use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use UuidPrimaryKey;
    use InteractsWithMedia;
    use WithData;

    public const minimumBeforeLowStock = 20;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
    ];

    protected $appends = [
        'badge',
    ];

    protected $dataClass = ProductData::class;

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getBadgeAttribute(): string
    {
        return ProductStatus::available($this->quantity);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product')->useDisk('product')->singleFile();
    }
}
