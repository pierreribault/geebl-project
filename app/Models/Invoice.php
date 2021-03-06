<?php

namespace App\Models;

use App\Actions\Invoices\GeneratePdfAction;
use App\Data\InvoiceData;
use App\Enums\InvoiceStatus;
use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model implements HasMedia
{
    use HasFactory;
    use UuidPrimaryKey;
    use InteractsWithMedia;
    use WithData;

    protected $fillable = [
        'transaction',
        'quantity',
        'status',
        'price',
        'total',
        'product_id',
        'company_id',
        'user_id',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];

    protected $appends = [
        'badge',
    ];

    protected $dataClass = InvoiceData::class;

    protected static function booted()
    {
        static::created(function ($ticket) {
            app(GeneratePdfAction::class)->generate($ticket);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getBadgeAttribute()
    {
        return $this->status->value;
    }
}
