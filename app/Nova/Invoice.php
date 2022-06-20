<?php

namespace App\Nova;

use App\Enums\InvoiceStatus;
use App\Nova\User;
use App\Nova\Company;
use App\Nova\Filters\Product\StatusFilter;
use App\Nova\Metrics\InvoicesPerDay;
use App\Nova\Metrics\TotalSalesPrice;
use App\Nova\Product;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Invoice extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice::class;

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
        'quantity',
        'status'
    ];

    public static $group = "Transaction";

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Select::make('Status', 'status')->options(InvoiceStatus::toSelectArray())->sortable()->required()->hideFromIndex(),
            Badge::make('Status', 'badge')->map(InvoiceStatus::colors()),
            BelongsTo::make('Product', 'product', Product::class),
            Currency::make('Price', 'price')->currency('eur')->sortable()->required(),
            Text::make('Quantity', 'quantity')->sortable()->required(),
            Currency::make('Total', 'total')->currency('eur')->sortable()->exceptOnForms()->required(),
            Date::make('Purchase date', 'created_at')->format('DD/MM/YYYY - hh:mm')->sortable()->required(),
            BelongsTo::make('Customer', 'user', User::class),
            BelongsTo::make('Company', 'company', Company::class),
            Files::make('PDF', 'invoice'),
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
        return [
            new InvoicesPerDay(),
            new TotalSalesPrice(),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return array_filter([
            StatusFilter::onlyOnIndex(),
        ]);
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

        return $query->whereRelation('company', 'id', $request->user()->company->id);
    }
}
