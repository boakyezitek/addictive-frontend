<?php

namespace App\Nova;

use App\Nova\Metrics\NewCreditPurchases;
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

class CreditPurchase extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CreditPurchase::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return __('L\'inscrit'). ' '. ($this->user->username ?? 'Utilisateur supprimé');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'user'
    ];

    public static function label()
    {
        return __('Achats de crédit');
    }

    public static function singularLabel()
    {
        return __('Achat de crédit');
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

        foreach(\App\Models\CreditPurchase::STATUSES as $status) {
            $statuses[$status] = trans('admin.credit_purchases.statuses.'.$status);
        }

        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Utilisateur'), 'user', AppUser::class)
                ->searchable(),
            Select::make('Statut', 'status')
                ->options($statuses)
                ->displayUsingLabels()
                ->required(),
            DateTime::make('Date', 'created_at')
                ->readonly()
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
            new NewCreditPurchases
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
