<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\AuthorTransformer;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Author extends Model implements HasMedia
{
    use InteractsWithMedia, ManageMedia, Eventable;

    /** @var int */
    public const RESIZED_WIDTH = 300;

    /** @var int */
    public const RESIZED_HEIGHT = 300;

    protected $fillable = ['first_name', 'last_name', 'description'];

    public static function transformer() : TransformerAbstract
    {
        return new AuthorTransformer();
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    public function audioBooks()
    {
        return $this->belongsToMany(AudioBook::class);
    }

    public function bonus()
    {
        return $this->hasManyThrough(Bonus::class, AudioBook::class);
    }

    public function home()
    {
        return $this->morphOne(HomeSection::class, 'homable');
    }

    public function getAudioBooksCount()
    {
        return $this->audioBooks()->count();
    }


    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/avatars')
            ->useDisk('gcs')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('resized')
              ->width(static::RESIZED_WIDTH)
              ->height(static::RESIZED_HEIGHT)
              ->performOnCollections($this->table.'/avatars');
    }
}
