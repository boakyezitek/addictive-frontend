<?php

namespace App\Transformers\V1;

use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class ChapterTransformer extends TransformerAbstract
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
    public function transform(Chapter $chapter)
    {
        $user = Auth::user();
        if($chapter->users->contains($user)){
            $progress = array(
                'step' => $chapter->getProgress($user),
                'duration' =>  $chapter->duration,
            );
        } else {
            $progress = array(
                'step' => 0,
                'duration' => $chapter->duration,
            );
        }
        return [
           'id' => (int) $chapter->id,
           'title' => $chapter->name,
           'progress' => $progress,
           'order' => $chapter->order,
           'have_audio' => $chapter->getMedia('chapters/audio')->count() >= 1 ? true : false,
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function downloadTransform(Chapter $chapter)
    {
        return [
           'id' => (int) $chapter->id,
           'audio' => $chapter->getMedia('chapters/audio')->count() >= 1 ? $chapter->getMediasTransformation()['audio'] : null,
        ];
    }
}
