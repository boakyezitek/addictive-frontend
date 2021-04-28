<?php

namespace App\Transformers\V1;

use App\Models\LoginScreenPicture;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class LoginScreenPictureTransformer extends TransformerAbstract
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
    public function transform(LoginScreenPicture $loginScreenPicture)
    {
        /*
        preg_match('/<div>(.*?)<strong>(.*?)<\/strong>(.*?)<\/div>/', $loginScreenPicture->text, $result);
        if(count($result) == 4){
            unset($result[0]);
            $text = implode('', $result);
            $start = strlen($result[1]);
            $end = $start + strlen($result[2]);
        } else {
            $start = 0;
            $end = 0;
            preg_match('/<div>(.*?)<\/div>/', $loginScreenPicture->text, $result);
            $text = count($result) == 2 ? $result[1] : $loginScreenPicture->text;
        }
        */

        return array_merge([
           'id' => (int) $loginScreenPicture->id,
           'order' => (int) $loginScreenPicture->order,
            /*
            'title' => [
                'text' => $text,
                'highlight' => [
                    'start' => $start,
                    'end' => $end
                ]
            ]
            */
        ], $loginScreenPicture->getMediasTransformation());
    }
}
