<?php

namespace App\Transformers\V1;

use App\Models\User;
use App\Models\Author;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
  protected $route;

  public function __construct($route = false)
  {
    $this->route = $route;
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
   * @param User $user
   *
   * @return array
   */
  public function transform(Author $author)
  { 
    if($this->route){
      $link = array(
        'id' => $author->id,
        'type' => 'author',
        'url' => route('authors.index', ['author' => $author]),
      );
      return [
       'id' => (int) $author->id,
       'first_name' => $author->first_name,
       'last_name' => $author->last_name,
       'description' => $author->description,
       'full_name' => $author->fullname,
       'audio_books_count' => $author->getAudioBooksCount(),
       'avatar' => $author->getMedia('authors/avatars')->count() >= 1 ? $author->getMediasTransformation()['avatars'] : null,
       'link' => $link,
      ];
    } else {
      return [
       'id' => (int) $author->id,
       'first_name' => $author->first_name,
       'last_name' => $author->last_name,
       'description' => $author->description,
       'full_name' => $author->fullname,
       'audio_books_count' => $author->getAudioBooksCount(),
       'avatar' => $author->getMedia('authors/avatars')->count() >= 1 ? $author->getMediasTransformation()['avatars'] : null,
      ];
    }
    
  }
}
