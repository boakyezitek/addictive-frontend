<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class CreditPack extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CreditPack::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return '['.__('admin.credit_packs.platforms.'.$this->platform).'] '.$this->name;
    }

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
        return __('Packs de crédit');
    }

    public static function singularLabel()
    {
        return __('Pack de crédit');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $platforms = [];

        foreach(\App\Models\CreditPack::PLATFORMS as $platform) {
            $platforms[$platform] = trans('admin.credit_packs.platforms.'.$platform);
        }

        return [
            ID::make()->sortable(),
            Text::make(__('Nom'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Select::make(__('Plateforme'), 'platform')
                ->sortable()
                ->options($platforms)
                ->displayUsingLabels()
                ->rules(['required']),
            Text::make(__('Réference'), 'reference')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Prix'), 'amount')
                ->sortable()
                ->currency('EUR')
                ->step(0.01)
                ->rules(['required']),
            Number::make(__('Crédits'), 'credits')
                ->step(1)
                ->min(1)
                ->rules(['required', 'min:1']),
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
