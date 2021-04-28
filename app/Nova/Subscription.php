<?php

namespace App\Nova;

use App\Nova\Metrics\NewSubscriptions;
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

class Subscription extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Subscription::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return __('L\'inscrit'). ' '. ($this->user->username ?? 'Utilisateur supprimé') .__(' a reçu '). ($this->plan->name ?? '');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
    ];

    public static function label()
    {
        return __('Abonnements');
    }

    public static function singularLabel()
    {
        return __('Abonnement');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $statuses = [];
        foreach(\App\Models\Subscription::STATUSES as $status) {
            $statuses[$status] = trans('admin.subscriptions.statuses.'.$status);
        }

        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Abonné'), 'user', AppUser::class),
            Select::make(__('Statut'), 'status')
                ->sortable()
                ->displayUsingLabels()
                ->options($statuses)
                ->rules(['required']),
            DateTime::make('Date', 'purchased_at')
                ->readonly(),
            DateTime::make('Expire le', 'expiration_at')
                ->readonly(),
            BelongsTo::make(__('Plan'), 'plan', Plan::class),
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
        return [
            new NewSubscriptions
        ];
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
