<?php

namespace App\Transformers\V1;

use App\Models\HomeSection;
use App\Models\LoginScreenPicture;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class HomeSectionTransformer extends TransformerAbstract
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
     * @param HomeSection $homeSection
     *
     * @return array
     */
    public function transform(HomeSection $homeSection)
    {
        $templateClass = $homeSection->getTemplateClass();
        $data = $templateClass::data($homeSection);
        $templateInformations = $templateClass::transformer($homeSection);
        if ($homeSection->template == 'feature') {
            return [
                'id' => (int) $homeSection->id,
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'description' => $data['description'] ?? null,
                'order' => (int) $homeSection->order,
                'type' => $homeSection->template,
                'items' => null,
                'buttons' => empty($templateInformations) ? null : $templateInformations,
                'cover' => $data['cover'] ?? null,

            ];
        } elseif($homeSection->template == 'dialog'){
            return [
               'id' => (int) $homeSection->id,
               'title' => $homeSection->title,
               'subtitle' => null,
               'description' => json_decode($homeSection->additional_information)->description ?? null,
               'order' => (int) $homeSection->order,
               'type' => $homeSection->template,
                'items' => !empty($data) ?  $data : null,
                'buttons' => empty($templateInformations) ? null : $templateInformations,
            ];
        } else {
            return [
               'id' => (int) $homeSection->id,
               'title' => $homeSection->title,
               'subtitle' => null,
               'description' => $templateInformations['description'] ?? null,
               'order' => (int) $homeSection->order,
               'type' => $homeSection->template,
                'items' => !empty($data) ?  $data : null,
                'buttons' => empty($templateInformations) ? null : $templateInformations,
            ];
        }
    }
}
