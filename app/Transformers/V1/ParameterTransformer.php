<?php

namespace App\Transformers\V1;

use App\Models\Parameter;
use League\Fractal\TransformerAbstract;

class ParameterTransformer extends TransformerAbstract
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
    public function transform(Parameter $parameter)
    {
        return [
           'id' => (int) $parameter->id,
           'about' => $parameter->about,
           'faq' => $parameter->faq,
           'privacy' => $parameter->privacy,
           'terms' => $parameter->terms,
        ];
    }
}
