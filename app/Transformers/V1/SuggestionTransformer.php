<?php

namespace App\Transformers\V1;

use App\Models\HomeSection;
use League\Fractal\TransformerAbstract;

class SuggestionTransformer extends TransformerAbstract
{
  protected $data;
  protected $title;

  public function __construct($data, $title)
  {
    $this->data = $data;
    $this->title = $title;
  }

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
   * @param HomeSection $homeSection
   *
   * @return array
   */
  public function transform(HomeSection $homeSection)
  {
      $data = $this->data;
      return [
          'id' => (int) $homeSection->id,
          'title' => $this->title,
          'subtitle' => null,
          'description' => null,
          'order' => (int) $homeSection->order,
          'type' => "collection",
          'items' => !empty($data) ?  $data : null,
          'buttons' => null,
          'cover' => null,

      ];
      
  }
}
