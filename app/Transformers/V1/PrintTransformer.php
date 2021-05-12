<?php

namespace App\Transformers\V1;

use Carbon\Carbon;
use App\Models\PrintBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;

class PrintTransformer extends TransformerAbstract
{
    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];



    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(PrintBook $print)
    {
        return [
            'id' => (int) $print->id,
            'release_date' => $print->release_date,
            'page_count' => $print->page_count,
            'isbn' => $print->isbn,
            'store_links' => $print->links ?? null,
        ];
    }
}
