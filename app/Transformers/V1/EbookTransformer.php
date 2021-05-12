<?php

namespace App\Transformers\V1;

use Carbon\Carbon;
use App\Models\Ebook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;

class EbookTransformer extends TransformerAbstract
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
    public function transform(Ebook $ebook)
    {
        return [
            'id' => (int) $ebook->id,
            'release_date' => $ebook->release_date,
            'store_links' => $ebook->links ?? null,
        ];
    }
}
