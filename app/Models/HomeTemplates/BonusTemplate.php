<?php


namespace App\Models\HomeTemplates;


use App\Nova\Bonus;
use App\Nova\AudioBook;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DigitalCreative\ConditionalContainer\ConditionalContainer;

class BonusTemplate extends Template
{
    public static string $key = 'bonus';

    public static function name(): string
    {
        return __('Bonus');
    }

    public static function fields() : array
    {
        return [
            Text::make(__('Label show more'), 'label_show_more')->required(),
            Select::make(__('Data type'))
                ->options([
                    Bonus::$model => Bonus::singularLabel(),
                ])->required(),
            Select::make(__('Request'))
                ->options(self::availableScopes())->required()
        ];
    }

    public static function transformer(Model $model) : array
    {
        $additional_informations = json_decode($model->additional_information);
        $array = [];
        if($additional_informations->data_type == 'App\Models\AudioBook') {
            $link = array(
                'id' => null,
                'type' => 'list_audiobook',
                'url' => route('audio_books.index', ['type' => $additional_informations->request, 'sorting' => 'desc']),
            );
        } elseif ($additional_informations->data_type == 'App\Models\Bonus') {
            $link = array(
                'id' => null,
                'type' => 'list_bonus',
                'url' => route('bonuses.index'),
            );
        }
        $array['neutral'] = array(
            'label' => $additional_informations->label_show_more,
            'emotion' => null,
            'link' => $link,
        );
        return (array) $array;
    }

    public static function data(Model $model)
    {
        $additionalInformation = json_decode($model->additional_information);
        $transformer =  $additionalInformation->data_type::transformer();

        return self::applyScope($additionalInformation->request, ($additionalInformation->data_type)::query(), $additionalInformation->data_type)
            ->take(5)
            ->get()
            ->map(function($model) use ($transformer) {
                return $transformer->homeTransform($model);
            });
    }

    public static function availableScopes() : array
    {
        return [
            'news' => 'Les plus récents',
            'olds' => 'Les plus anciens',
            'most_searched' => 'Les plus populaires',
            'unpublished' => 'Les prochaines sorties'
        ];
    }

    private static function applyScope(string $scope, Builder $query, String $model) : Builder
    {
        switch($model) {
            case 'App\Models\AudioBook': 
                switch ($scope) {
                    case 'news':
                        $query = $query->published()->orderBy('publication_date', 'DESC');
                        break;
                    case 'olds':
                        $query = $query->published()->orderBy('publication_date', 'ASC');
                        break;
                    case 'most_searched':
                        // TODO: order by visits count
                        $query = $query->published();
                        break;
                    case 'unpublished':
                        $query = $query->unpublished();
                        break;
                }
                break; 
            case 'App\Models\Bonus':
                $query = $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
    }
}
