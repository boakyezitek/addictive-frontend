<?php


namespace App\Models\Traits;


use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Eventable
{

    public static array $events = [
        'updated', 'created', 'deleted'
    ];

    public function events()
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    public static function bootEventable()
    {
        foreach(self::$events as $event){
            static::$event(function (Model $model) use ($event){
                $guard = self::activeGuard() ?? 'unknown';
                if (get_class($model) == "App\Models\Subscription" || get_class($model) == "App\Models\Credit") {
                    Event::create([
                        'eventable_type' => get_class($model),
                        'eventable_id' => $model->id,
                        'owner_type' => "App\Models\User",
                        'owner_id' => $model->toArray()['user_id'],
                        'description' => $model->toJson(),
                         'action' => $event
                    ]);
                } else {
                    Event::create([
                        'eventable_type' => get_class($model),
                        'eventable_id' => $model->id,
                        'owner_type' => $guard == 'unknown' ? null : getModelForGuard($guard),
                        'owner_id' => $guard == 'unknown' ? null : Auth::guard($guard)->user()->id,
                        'description' => $model->toJson(),
                         'action' => $event
                    ]);
                }
            });
        }
    }

    static private function activeGuard() : ?string
    {
        foreach(array_keys(config('auth.guards')) as $guard){
            if(auth()->guard($guard)->check()) return $guard;
        }
        return null;
    }
}
