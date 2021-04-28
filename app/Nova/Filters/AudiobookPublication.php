<?php

namespace App\Nova\Filters;

use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Ampeco\Filters\DateRangeFilter;

class AudiobookPublication extends DateRangeFilter
{
    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return 'Date de publication';
    }

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
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->whereBetween('publication_date', [$from, $to]);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $models = Tag::all();
        return $models->pluck('id', 'name')->all();
    }
}
