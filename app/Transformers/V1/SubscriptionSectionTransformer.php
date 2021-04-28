<?php

namespace App\Transformers\V1;

use App\Models\SubscriptionSection;
use League\Fractal\TransformerAbstract;

class SubscriptionSectionTransformer extends TransformerAbstract
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
  public function transform(SubscriptionSection $subscription_section)
  { 
    return [
     'id' => (int) $subscription_section->id,
     'title' => $subscription_section->title,
     'description' => $subscription_section->description,
     'icon' => $subscription_section->icon,
     'order' => $subscription_section->order
    ];
  }
}
