<?php

namespace App\Nova;

use App\Enums\TicketStatus;
use App\Nova\Actions\RefundTicket;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Ticket extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Ticket::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static $group = "Events";

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Select::make('Status')->options(TicketStatus::getKeysValues()),
            HasOne::make('Category', 'category', TicketCategory::class)->sortable(),
            HasOne::make('User', 'user', User::class)->sortable(),
            HasOne::make('Event', 'event', Event::class)->sortable(),
            Text::make('QRCode', function () {
                return (string) QrCode::size(150)->generate($this->id);
            })->asHtml()->hideFromIndex(),
            Currency::make('Price')
                ->currency('eur')
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
        return [
            new RefundTicket
        ];
    }

    /**
     * We don't want to use policies for this action (create a ticket should be made from event resource).
     *
     * @param Request $request
     * @return void
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
}
