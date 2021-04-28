<?php

namespace App\Nova;

use R64\NovaFields\JSON;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use App\Models\HomeTemplates\DialogTemplate;
use App\Models\HomeTemplates\FeatureTemplate;
use App\Models\HomeTemplates\CollectionTemplate;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;
use DigitalCreative\ConditionalContainer\ConditionalContainer;
use DigitalCreative\ConditionalContainer\HasConditionalContainer;

class HomeSection extends ResourceForUser
{
    use HasConditionalContainer, HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\HomeSection::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
    ];

    public static function label()
    {
        return __('Home Sections');
    }

    public static function singularLabel()
    {
        return __('Home Section');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $options = array();

        foreach(\App\Models\HomeSection::TEMPLATES as $template){
            $options[$template::$key] = $template::name();
        }

        $fields = [
            ID::make()->sortable(),

            Text::make(__('Title'), 'title'),
            Number::make(__('Order'), 'order'),
            Select::make(__('Template'), 'template')
                ->options($options)
                ->required()->resolveUsing(function ($template) {
                    return __($template);
                }),
        ];

        foreach(\App\Models\HomeSection::TEMPLATES as $template){
            $fields[] = ConditionalContainer::make([
                    JSON::make(__('Content'), $template::fields(), 'additional_information')
                ])
                ->if('template = '.($template::$key));
            if(in_array($template::$key, [FeatureTemplate::$key])){
                $fields[] = ConditionalContainer::make([
                    MorphTo::make(__('Data'), 'homeSectionable')
                        ->types([
                            AudioBook::class
                        ])->searchable()
                ])->if('template = '.($template::$key));
            }elseif(in_array($template::$key, [DialogTemplate::$key])){
                $fields[] = ConditionalContainer::make([
                    Boolean::make(__('Is rating'), 'is_rating'),
                    Boolean::make(__('Is subscription offer'), 'is_free_subscription')
                ])->if('template = '.($template::$key));
            }
        }

        return $fields;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
