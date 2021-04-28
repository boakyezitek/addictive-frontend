<?php

namespace App\Transformers\V1;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        '',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
           'id' => (int) $category->id,
           'name' => $category->name,
        ];
    }
}
