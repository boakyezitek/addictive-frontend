<?php


namespace App\Models\HomeTemplates;


use App\Nova\AudioBook;
use App\Models\Reaction;
use App\Nova\Subscription;
use App\Nova\CreditPurchase;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\MorphTo;
use R64\NovaFields\Autocomplete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DigitalCreative\ConditionalContainer\ConditionalContainer;

class FeatureTemplate extends Template
{
    public static string $key = 'feature';

    public static function name(): string
    {
        return __('Feature');
    }

    public static function fields() : array
    {
        return [
            Text::make(__('Button label'), 'btn_label')->required(),
            Select::make(__('Button emotion'), 'btn_emotion')
                ->options(Reaction::FEELINGS),

        ];
    }

    public static function transformer(Model $model) : array
    {
        $data = json_decode($model->additional_information);
        $array = [];
        if($model->home_sectionable_type == 'App\Models\AudioBook') {
            $link = array(
                'id' => $model->home_sectionable_id,
                'type' => 'audiobook',
                'url' => route('audio_books.show', ['audio_book' => $model->home_sectionable_id]),
            );
        }
        $array['neutral'] = array(
            'label' => $data->btn_label,
            'emotion' => $data->btn_emotion ?? null,
            'link' => $link,
        );

        return (array) $array;
    }

    public static function data(Model $model)
    {
        return $model->homeSectionable->transformer()->homeTransform($model->homeSectionable);
    }
}
