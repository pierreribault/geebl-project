<?php

namespace App\Models;

use Spatie\LaravelData\WithData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artist extends Model
{
    use HasFactory;
    use WithData;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
