<?php


namespace App\Models\HomeTemplates;


use App\Nova\AudioBook;
use App\Models\Reaction;
use Timothyasp\Color\Color;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Textarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DigitalCreative\ConditionalContainer\ConditionalContainer;

class DialogTemplate extends Template
{
    public static string $key = 'dialog';

    public static function name(): string
    {
        return __('Dialog');
    }

    public static function fields() : array
    {
        return [
            Textarea::make(__('Description '), 'description'),
            Boolean::make(__('Closable'), 'is_closable'),
            Heading::make('Bouton refuser'),
            Text::make(__('Button Left Title'), 'btn_left_label')->required(),
            Select::make(__('Button Left emotion'), 'btn_left_emotion')
                ->options(Reaction::FEELINGS),
            Heading::make('Bouton accepter'),
            Text::make(__('Button Right Title'), 'btn_right_label')->required(),
            Select::make(__('Button Right type'), 'btn_right_type')
                ->options(self::availableLabels())->required(),
            Select::make(__('Button Right emotion'), 'btn_right_emotion')
                ->options(Reaction::FEELINGS),
        ];
    }

    public static function transformer(Model $model) : array
    {
        $data = json_decode($model->additional_information);
        if($data->btn_right_type == 'trial') {
            return [
                'is_closable' => $data->is_closable,
                'positive' => [
                    'label' => $data->btn_right_label,
                    'emotion' => $data->btn_right_emotion,
                    'link' => [
                        'id' => null,
                        'type' => $data->btn_right_type,
                        'url' => route('trial.true'),
                    ],
                ],
                'negative' => [
                    'label' => $data->btn_left_label,
                    'emotion' => $data->btn_left_emotion,
                    'link' => [
                        'id' => null,
                        'type' => $data->btn_right_type,
                        'url' => route('trial.false'),
                    ],
                ]
            ];
        }
        return [
            'is_closable' => $data->is_closable,
            'positive' => [
                'label' => $data->btn_right_label,
                'emotion' => $data->btn_right_emotion,
                'link' => [
                    'id' => null,
                    'type' => $data->btn_right_type,
                    'url' => route('rating.true'),
                ],
            ],
            'negative' => [
                'label' => $data->btn_left_label,
                'emotion' => $data->btn_left_emotion,
                'link' => [
                    'id' => null,
                    'type' => $data->btn_right_type,
                    'url' => route('rating.false'),
                ],
            ]
        ];
    }

    public static function availableLabels() : array
    {
        return [
            'trial' => 'Période d\'essai',
            'rating' => 'Notez l\'application'
        ];
    }

    public static function data(Model $model)
    {
        return null;
    }
}
