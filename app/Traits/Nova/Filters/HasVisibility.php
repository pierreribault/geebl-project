<?php

namespace App\Traits\Nova\Filters;

trait HasVisibility
{
    /**
     * Check if the request has viaResource query param.
     * If it does, that means we are on a related resource.
     *
     * @return bool
     */
    public static function requestFromIndex(): bool
    {
        return request()?->query('viaResource') === null;
    }

    /**
     * If the request is from index, return the filter.
     *
     * @return static|null
     */
    public static function onlyOnIndex(): static|null
    {
        return (static::requestFromIndex()) ? new static() : null;
    }

    /**
     * If the request is from resource, return the filter.
     *
     * @return static|null
     */
    public static function onlyOnDetail(): static|null
    {
        return (!static::requestFromIndex()) ? new static() : null;
    }
}
