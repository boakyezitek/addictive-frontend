<?php

namespace App\Nova;

use App\Models\Traits\Eventable;
use Eminiarts\NovaPermissions\Nova\ResourceForUser;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Nova;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Event extends ResourceForUser
{

    use HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        if($this->owner) {
            return $this->owner->fullName. ' '.$this->action.' '.$this->eventable_type.'::'.$this->eventable_id;
        } else {
            return $this->action.' '.$this->eventable_type.'::'.$this->eventable_id;
        }
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'action', 'description'
    ];

    public static function label()
    {
        return __('Évenements');
    }

    public static function singularLabel()
    {
        return __('Évenement');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $events = [];
        foreach(Eventable::$events as $event){
            $events[$event] = trans('admin.events.'.$event);
        }

        return [
            ID::make('Id', 'id')
                ->rules('required')
                ->hideFromIndex()
            ,
            MorphTo::make(__('Utilisateur'), 'owner')
                ->types([
                    User::class,
                    AppUser::class
                ]),
            MorphTo::make(__('Évenement'), 'eventable')
                ->types([
                    CreditPurchase::class,
                    Subscription::class,
                    Credit::class
                ]),
            Select::make('Action', 'action')
                ->required()
                ->options($events)
                ->displayUsingLabels(),
            Code::make(__('Object'), 'description')->json()
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
