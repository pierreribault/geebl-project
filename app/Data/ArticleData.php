<?php

namespace App\Data;

use Carbon\Carbon;
use App\Enums\ArticleStatus;
use App\Models\Article;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Illuminate\Support\Str;
class ArticleData extends Data
{
    public function __construct(
        public readonly ?string $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $content,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly Carbon $date,
        public readonly ?string $article_url,
        public readonly Lazy|UserData $redactor,
        #[WithCast(EnumCast::class)]
        public readonly ?ArticleStatus $status = ArticleStatus::Draft,
    ) {
    }

    public static function fromModel(Article $article): self
    {
        return self::from([
            ...$article->toArray(),
            'article_url' => $article->getFirstMediaUrl('article'),
            'redactor' => Lazy::create(fn () => UserData::from($article->redactor)),
        ]);
    }

    public function with(): array
    {
        return [
            'beautiful_date' => $this->date->toFormattedDateString(),
            'short_content' => Str::limit($this->content, 400),
        ];
    }
}
