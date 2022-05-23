<?php

namespace App\Http\Controllers;

use App\Data\ArtistData;
use App\Models\Artist;
use Inertia\Inertia;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        return Inertia::render('Artist/Show', [
            'artist' => ArtistData::fromModel($artist),
        ]);
    }
}
