<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Transformers\V1\LoginScreenPictureTransformer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LoginScreenPicture extends Model implements HasMedia, Sortable, Transformable
{

    use InteractsWithMedia, ManageMedia, SortableTrait, Eventable;

    /** @var int */
    public const RESIZED_WIDTH = 300;

    /** @var int */
    public const RESIZED_HEIGHT = 300;

    protected $fillable = ['order', 'text'];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public static function transformer() : TransformerAbstract
    {
        return new LoginScreenPictureTransformer();
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/covers')
            ->useDisk('gcs')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resized')
              ->width(static::RESIZED_WIDTH)
              ->height(static::RESIZED_HEIGHT)
              ->performOnCollections($this->table.'/covers');
    }
}
