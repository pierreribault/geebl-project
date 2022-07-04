<?php

namespace App\Nova\Filters\Product;

use App\Traits\Nova\Filters\HasVisibility;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class OutOfStockFilter extends BooleanFilter
{
    use HasVisibility;

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return ($value['out_of_stock'])
            ? $query->where('quantity', '<=', 0)
            : $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Out of Stock' => 'out_of_stock',
        ];
    }
}
