<?php

namespace App\Data;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Min;


class CompanyData extends Data
{
    public function __construct(
        public readonly ?int $id,

        #[Required]
        public string $name,

        #[Required]
        public string $crn,

        #[Required]
        public string $location
    ) {
    }
}
