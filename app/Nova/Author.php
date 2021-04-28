<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Author extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Author::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'first_name', 'last_name'
    ];

    public static function label()
    {
        return __('Authors');
    }

    public static function singularLabel()
    {
        return __('Author');
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
            Images::make(__('Image'), 'authors/avatars')
                ->setFileName(function($originalFilename, $extension, $model){
                    return md5($originalFilename) . '.' . $extension;
                }
            )->rules('required'),
            Text::make(__('FirstName'), 'first_name')
                ->sortable()
                ->rules('required', 'max:255'),
            Text::make(__('LastName'), 'last_name')
                ->sortable()
                ->rules('required', 'max:255'),
            Textarea::make(__('Biography'), 'description')
                ->required()
                ->alwaysShow(),
            BelongsToMany::make(__('AudioBooks'), 'audioBooks', AudioBook::class, ),
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
