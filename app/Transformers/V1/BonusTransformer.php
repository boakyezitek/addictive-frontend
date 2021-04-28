<?php

namespace App\Transformers\V1;

use Carbon\Carbon;
use App\Models\Bonus;
use App\Models\AudioBook;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\HomeTransformable;

class BonusTransformer extends TransformerAbstract implements HomeTransformable
{
    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(Bonus $bonus)
    {
        return [
            'id' => (int) $bonus->id,
            'title' => $bonus->name,
            'subtitle' => $bonus->subtitle,
            'introduction' => $bonus->introduction,
            'description' => $bonus->formatDescription(),
            'is_locked' => $bonus->lockedStatus(),
            'cover' => $bonus->getMedia('bonus/covers')->count() >= 1 ? $bonus->getMediasTransformation()['covers'] : null,
            'uri' => [
                'id' => $bonus->id,
                'type' => 'bonus',
                'url' => route('bonuses.show', ['bonus' => $bonus->id]),
            ],
            'have_audio' => $bonus->getMedia('bonus/audios')->count() >= 1 ? true : false,
            'have_video' => $bonus->getMedia('bonus/videos')->count() >= 1 ? true : false,
            'created_at' => $bonus->created_at->toIso8601String(),
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function audioTransform(Bonus $bonus)
    {
        return [
           'id' => (int) $bonus->id,
           'audio' => $bonus->getMedia('bonus/audios')->count() >= 1 ? $bonus->getMediasTransformation()['audios'] : null,
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     *
     * @return array
     */
    public function videoTransform(Bonus $bonus)
    {
        return [
           'id' => (int) $bonus->id,
           'video' => $bonus->getMedia('bonus/videos')->count() >= 1 ? $bonus->getMediasTransformation()['videos'] : null,
        ];
    }

    /**
     * Turn this item object into a generic array
     *
     * @param Model $model
     *
     * @return array
     */
    public static function homeTransform(Model $model) : array
    {
        return [
            'id' => (int) $model->id,
            'title' => $model->name,
            'subtitle' => $model->subtitle,
            'is_locked' => $model->lockedStatus(),
            'cover' => $model->getMedia('bonus/covers')->count() >= 1 ? $model->getMediasTransformation()['covers'] : null,
            'uri' => [
                'id' => $model->id,
                'type' => 'bonus',
                'url' => route('bonuses.show', ['bonus' => $model->id]),
            ],
            'have_audio' => $model->getMedia('bonus/audios')->count() >= 1 ? true : false,
            'have_video' => $model->getMedia('bonus/videos')->count() >= 1 ? true : false,
        ];
    }
}
