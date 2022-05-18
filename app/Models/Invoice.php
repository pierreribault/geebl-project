<?php

namespace App\Models;

use App\Models\User;
use App\Models\Seller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction',
        'quantity',
        'status',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
    ];

    protected $appends = [
        'price'
    ];

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

    public function getPriceAttribute()
    {
        return round((float)$this->product->price * $this->quantity, 2);
    }
}
