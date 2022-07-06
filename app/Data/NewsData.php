<?php

namespace App\Data;

use Carbon\Carbon;
use App\Enums\NewsStatus;
use App\Models\News;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class NewsData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $content,
        public readonly Lazy|UserData $redactor,
        public readonly Lazy|EventData $event,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $date,
        #[WithCast(EnumCast::class)]
        public readonly ?NewsStatus $status = NewsStatus::Draft,
    ) {
    }

    public static function fromModel(News $news): self
    {
        return self::from([
            ...$news->toArray(),
            'redactor' => Lazy::create(fn () => UserData::from($news->redactor)),
            'event' => Lazy::create(fn () => EventData::from($news->event)),
        ]);
    }

    public function with(): array
    {
        return [
            'beautiful_date' => $this->date->toFormattedDateString(),
        ];
    }
}
