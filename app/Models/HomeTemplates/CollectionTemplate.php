<?php


namespace App\Models\HomeTemplates;


use App\Nova\AudioBook;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class CollectionTemplate extends Template
{
    public static string $key = 'collection';

    public static function name(): string
    {
        return __('Collection');
    }

    public static function fields() : array
    {
        return [
            Text::make(__('Label show more'), 'label_show_more')->required(),
            Select::make(__('Data type'), 'data_type')
                ->options([
                    AudioBook::$model => AudioBook::singularLabel(),
                ])->required(),
            Select::make(__('Request'), 'request')
                ->options(self::availableScopes())->required()
        ];
    }

    public static function transformer(Model $model) : array
    {
        $additional_informations = json_decode($model->additional_information);
        $array = [];
        if($additional_informations->data_type == 'App\Models\AudioBook') {
            if($additional_informations->request == 'unpublished' || $additional_informations->request == 'olds') {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $link = array(
                'id' => null,
                'type' => 'list_audiobook',
                'url' => route('audio_books.index', ['type' => $additional_informations->request, 'sorting' => $order]),
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
                        $query = $query->where('is_visible', 1)->filters($scope, 'publication_date', 'desc', null);
                        break;
                    case 'olds':
                        $query = $query->where('is_visible', 1)->filters($scope, 'publication_date', 'ASC', null);
                        break;
                    case 'most_searched':
                        // TODO: order by visits count
                        $query = $query->where('is_visible', 1)->filters($scope, '', 'desc', null);
                        break;
                    case 'unpublished':
                        $query = $query->where('is_visible', 1)->filters($scope, 'publication_date', 'asc', null);
                        break;
                }
                break; 
        }

        return $query->where('is_visible', 1);
    }
}
