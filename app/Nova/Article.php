<?php

namespace App\Nova;

use App\Enums\ArticleStatus;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Illuminate\Support\Str;

class Article extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Article::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
        'slug',
        'content'
    ];

    public static $group = "Administration";

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Title', 'title')
                ->sortable()
                ->required()
                ->displayUsing(fn ($text) => Str::limit($text, 50)),

            Text::make(
                'See the article',
                fn ($article) => $article->isPublished()
                ? sprintf('<a href="%s" target="_blank">Link</a>', route('articles.show', $article->slug))
                : 'Not yet published'
            )->asHtml()->onlyOnDetail(),

            Slug::make('Slug')
                ->from('title')
                ->sortable()
                ->required(),

            Text::make('Description', 'description')
                ->sortable()
                ->required()
                ->hideFromIndex(),

            Images::make('Main image', 'article')
                ->required()
                ->croppingConfigs(['ratio' => 16 / 5])
                ->mustCrop(),

            Markdown::make('Content', 'content')
                ->sortable()
                ->required(),

            Select::make('Status', 'status')
                ->options(ArticleStatus::toSelectArray())
                ->sortable()
                ->required()
                ->hideFromIndex(),

            Badge::make('Status', 'badge')
                ->map(ArticleStatus::colors()),

            Date::make('Date', 'date')
                ->sortable()
                ->required(),

            BelongsTo::make('Redactor', 'redactor', User::class),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
