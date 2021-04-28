<?php


namespace App\Models\HomeTemplates;


use App\Nova\AudioBook;
use App\Models\Reaction;
use App\Nova\Subscription;
use Timothyasp\Color\Color;
use App\Nova\CreditPurchase;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\MorphTo;
use R64\NovaFields\Autocomplete;
use App\Models\AudioBook as Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DigitalCreative\ConditionalContainer\ConditionalContainer;

class CurrentlyPlayedTemplate extends Template
{
    public static string $key = 'currently_played';

    public static function name(): string
    {
        return __('Currently Played');
    }

    public static function fields() : array
    {
        return [
            Heading::make('Rien à inscrire'),
        ];
    }

    public static function transformer(Model $model) : array
    {
        $array = json_decode($model->additional_information);
        
        return (array) $array;
    }

    public static function data(Model $model)
    {
        $additionalInformation = json_decode($model->additional_information);
        $transformer =  Book::transformer();

        $user = Auth::user();

        return $user->audioBooks()
            ->where('status', Book::STATUS_IN_PROGRESS)
            ->whereNull('archived_at')
            ->take(5)
            ->get()
            ->map(function($model) use ($transformer) {
                return $transformer->homeTransform($model);
            });
    }
}
