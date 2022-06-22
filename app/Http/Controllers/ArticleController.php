<?php

namespace App\Http\Controllers;

use App\Data\ArticleData;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function show(Article $article)
    {
        if (! $article->isPublished()) {
            abort(404);
        }

        return Inertia::render('Article/Show', [
            'article' => ArticleData::fromModel($article)->include('redactor'),
            'date' => $article->date->toFormattedDateString(),
        ]);
    }
}
