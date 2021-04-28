<?php

namespace App\Nova\Filters;

use App\Models\AudioBook;
use Illuminate\Http\Request;
use Oleksiypetlyuk\NovaRangeFilter\NovaRangeFilter;

class AudiobookPrice extends NovaRangeFilter
{
    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return 'Prix';
    }

    public function __construct()
    {
        $this->min = floor(AudioBook::min('price'));

        $this->max = ceil(AudioBook::max('price'));

        $this->tooltip = "active";

        parent::__construct();
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
        return $query->whereBetween('price', $value)
            ->orWhereNull('price');
    }

}
