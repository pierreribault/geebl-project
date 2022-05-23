<?php

namespace App\Nova;

use App\Nova\Artist;
use App\Enums\EventStatus;
use Spatie\TagsField\Tags;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;

class Event extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->canSee(fn ($request) => $request->user()->isAdmin()),
            Text::make('Name', 'name')->sortable()->required(),
            Slug::make(__('Slug'), 'slug')->from('name')->rules('required', 'unique:events,slug,'. $this->resource->id)->sortable(),
            Text::make('Location', 'location')->sortable()->required(),
            Date::make('Date', 'date')->sortable()->required(),
            Text::make('Description', 'description')->sortable()->required(),
            Number::make('Price', 'price')->step('0.01')->min(1)->sortable()->required(),
            Number::make('Seats', 'seats')->min(1)->sortable()->required(),
            Select::make('Status', 'status')->options(EventStatus::getKeysValues())->sortable(),
            BelongsTo::make('Author', 'author', User::class),
            HasMany::make('Slots', 'slots', Slot::class),
            Tags::make('Kinds')->type('kinds')->required(),
            BelongsToMany::make('Artists', 'artists', Artist::class),
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->isAdmin()) {
            return $query;
        }

        return $query->whereRelation('author.company', 'id', $request->user()->company->id);
    }
}
