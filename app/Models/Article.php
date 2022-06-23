<?php

namespace App\Models;

use App\Data\ArticleData;
use App\Enums\ArticleStatus;
use App\Traits\UuidPrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory;
    use WithData;
    use UuidPrimaryKey;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'content',
        'date',
        'status',
        'redactor_id',
    ];

    protected $casts = [
        'status' => ArticleStatus::class,
        'date' => 'date:Y-m-d',
    ];

    protected $appends = [
        'badge',
    ];

    protected $dataClass = ArticleData::class;

    public function redactor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBadgeAttribute()
    {
        return $this->status->value;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article')->useDisk('article')->singleFile();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query): void
    {
        $query->where('status', ArticleStatus::Published);
    }

    public function isPublished(): bool
    {
        return $this->status === ArticleStatus::Published;
    }

    public function isDraft(): bool
    {
        return $this->status === ArticleStatus::Draft;
    }

    public function isScheduled(): bool
    {
        return $this->status === ArticleStatus::Scheduled;
    }
}
