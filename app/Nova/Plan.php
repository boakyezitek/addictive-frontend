<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Ebess\AdvancedNovaMediaLibrary\Fields\Media;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Plan extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Plan::class;

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
        return __('Formules d\'abonnement');
    }

    public static function singularLabel()
    {
        return __('Formule d\'abonnement');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $intervals = [];
        $platforms = [];

        foreach(\App\Models\Plan::INTERVALS as $interval) {
            $intervals[$interval] = trans('admin.plans.intervals.'.$interval);
        }

        foreach(\App\Models\Plan::PLATFORMS as $platform) {
            $platforms[$platform] = trans('admin.plans.platforms.'.$platform);
        }

        return [
            ID::make()->sortable(),
            Text::make(__('Nom'), 'name')
                ->sortable()
                ->rules('required', 'max:255'),
            Select::make(__('Plateforme'), 'platform')
                ->sortable()
                ->options($platforms)
                ->rules(['required']),
            Text::make(__('Réference'), 'reference')
                ->sortable()
                ->rules('required', 'max:255'),
            Currency::make(__('Prix'), 'amount')
                ->sortable()
                ->currency('EUR')
                ->step(0.01)
                ->rules(['required']),
            Number::make(__('Fréquence (tous les)'), 'interval_count')
                ->step(1)
                ->min(1)
                ->rules(['required', 'min:1']),
            Select::make(__('Fréquence (par)'), 'interval')
                ->options($intervals)
                ->rules(['required']),
            Number::make(__('Crédits'), 'credits')
                ->step(1)
                ->min(1)
                ->rules(['required', 'min:1']),
            HasMany::make(__('Subscriptions'), 'subscriptions', Subscription::class),
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
