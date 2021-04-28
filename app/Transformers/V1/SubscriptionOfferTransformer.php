<?php

namespace App\Transformers\V1;

use App\Models\SubscriptionOffer;
use League\Fractal\TransformerAbstract;

class SubscriptionOfferTransformer extends TransformerAbstract
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
    public function transform(SubscriptionOffer $subscriptionOffer)
    {   
        $title = $subscriptionOffer->formatTitle();
        

        return [
            'id' => (int) $subscriptionOffer->id,
            'title' => [
                'text' => $title['text'],
                'highlight' => [
                    'start' => $title['start'],
                    'end' => $title['end']
                ]
            ],
            'description' => $subscriptionOffer->description,
            'advantages' => $subscriptionOffer->formatAdvantages()
        ];
    }
}
