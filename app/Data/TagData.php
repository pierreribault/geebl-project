<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class TagData extends Data
{
    public function __construct(
        public ?int $id,
        public array $name,
    ) {
    }
}
