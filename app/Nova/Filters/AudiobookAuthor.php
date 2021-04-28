<?php

namespace App\Nova\Filters;

use App\Models\Author;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class AudiobookAuthor extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return 'Auteurs';
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
        return $query->whereHas('authors', function($query) use ($value) {
            $query->where('authors.id', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $models = Author::all();
        return $models->pluck('id', 'last_name')->all();
    }
}
