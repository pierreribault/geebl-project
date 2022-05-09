<?php

namespace App\Models;

use App\Data\CompanyData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Company extends Model
{
    use HasFactory;
    use WithData;

    protected $dataClass = CompanyData::class;

    protected $fillable = [
        'name',
        'crn',
        'location',
    ];
}
