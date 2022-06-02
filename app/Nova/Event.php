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
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;

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
            Date::make('Start at', 'start_at')->sortable()->required(),
            Date::make('End at', 'end_at')->sortable()->required(),
            Text::make('Description', 'description')->sortable()->required(),
            Select::make('Status', 'status')->options(EventStatus::getKeysValues())->sortable(),
            BelongsTo::make('Author', 'author', User::class),
            Tags::make('Kinds')->type('kinds')->required(),
            BelongsToMany::make('Artists', 'artists', Artist::class),
            HasMany::make('Categories', 'ticketsCategories', TicketCategory::class),
            HasMany::make('Tickets', 'tickets', Ticket::class),
            Files::make('Cover', 'cover')->required(),
            BelongsTo::make('City', 'city', City::class)->sortable(),
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

        return $query->whereRelation('author', 'id', $request->user()->company->id);
    }
}
