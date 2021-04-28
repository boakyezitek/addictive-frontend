<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use Spatie\EloquentSortable\SortableTrait;
use App\Transformers\V1\SubscriptionSectionTransformer;

class SubscriptionSection extends Model implements Sortable, Transformable
{

    use SortableTrait, Eventable;

    protected $fillable = ['title', 'order', 'icon', 'description',];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public static function transformer(): TransformerAbstract
    {
        return new SubscriptionSectionTransformer();
    }
}
