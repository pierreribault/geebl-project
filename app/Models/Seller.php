<?php

namespace App\Models;

use App\Data\SellerData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Spatie\LaravelData\WithData;

class Seller extends Model
{
    use HasFactory;
    use WithData;

    protected $dataClass = SellerData::class;

    protected $fillable = [
        'user_id',
        'company_id',
        'is_owner',
        'is_redactor',
        'is_reviewer',
        'is_consumer',
    ];

    protected $appends = [
        'title',
    ];

    public function isOwner(): bool
    {
        return $this->is_owner;
    }

    public function isRedactor(): bool
    {
        return $this->is_redactor;
    }

    public function isReviewer(): bool
    {
        return $this->is_reviewer;
    }

    public function isConsumer(): bool
    {
        return $this->is_consumer;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'author_id');
    }

    public function getTitleAttribute(): string
    {
        return Str::title($this->user->name);
    }
}
