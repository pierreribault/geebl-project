<?php

namespace App\Models;

use App\Traits\UuidPrimaryKey;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use UuidPrimaryKey;
}