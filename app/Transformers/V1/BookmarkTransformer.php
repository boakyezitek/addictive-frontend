<?php

namespace App\Transformers\V1;

use App\Models\Bookmark;
use League\Fractal\TransformerAbstract;

class BookmarkTransformer extends TransformerAbstract
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
    public function transform(Bookmark $bookmark)
    {
        return [
           'id' => (int) $bookmark->id,
           'title' => $bookmark->name,
           'from' => $bookmark->from,
           'to' => $bookmark->to,
           'duration' => $bookmark->getDuration(),
           'label' => $bookmark->getLabel(),
           'reaction' => $bookmark->reaction ? $bookmark->reaction->feeling : null,
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function syncTransform(Bookmark $bookmark)
    {
        return [
           'id' => (int) $bookmark->id,
           'chapter_id' => (int) $bookmark->chapter->id,
           'title' => $bookmark->name,
           'from' => $bookmark->from,
           'to' => $bookmark->to,
           'duration' => $bookmark->getDuration(),
           'label' => $bookmark->getLabel(),
           'reaction' => $bookmark->reaction ? $bookmark->reaction->feeling : null,
           'timestamp_reference' => $bookmark->timestamp_reference,
           'created_at' => $bookmark->created_at ? $bookmark->created_at->toIso8601String() : null,
           'updated_at' => $bookmark->updated_at ? $bookmark->updated_at->toIso8601String() : null,
           'deleted_at' => $bookmark->deleted_at ? $bookmark->deleted_at->toIso8601String() : null,
           'synchronized_at' => $bookmark->synchronized_at ? $bookmark->synchronized_at->toIso8601String() : null,
        ];
    }
}
