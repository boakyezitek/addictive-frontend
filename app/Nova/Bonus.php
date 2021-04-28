<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Bonus extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bonus::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];

    public static function label()
    {
        return __('Bonuses');
    }

    public static function singularLabel()
    {
        return __('Bonus');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Images::make(__('Image'), 'bonus/covers')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            )->required(),
            BelongsTo::make(__('AudioBook'), 'audioBook', AudioBook::class),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('Subtitle'), 'subtitle')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make(__('Resume'), 'introduction')
                ->rules('required'),
            KeyValue::make(__('Sections'), 'sections')
                ->keyLabel(__('Title'))
                ->valueLabel(__('Content'))
                ->rules('json'),
            Files::make(__('Audio file'), 'bonus/audios')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            ),
            Files::make(__('Video file'), 'bonus/videos')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            ),
        ];
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
