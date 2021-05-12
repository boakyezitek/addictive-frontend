<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Builder;
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

    protected $fillable = ['first_name', 'last_name', 'description', 'is_web', 'edisource_id'];

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

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_author');
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

    public function getBooksCount()
    {
        return $this->books()->count();
    }

    /**
     * Scope a query to only include authors that match given filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, $letter = null, $string = null, $format = null)
    {
        if ($letter) {
            $query = $query->where('first_name', 'LIKE', $letter.'%');
        }

        if ($string) {
            $query = $query->where('first_name', 'like', "%{$string}%")->orWhere('last_name', 'like', "%{$string}%");
        }

        if ($format) {
            switch ($format) {
                case 'print':
                    $query = $query->whereHas('books', function (Builder $query) {
                        $query->has('print');
                    });
                    # code...
                    break;
                case 'ebook':
                    $query = $query->whereHas('books', function (Builder $query) {
                        $query->has('ebook');
                    });
                    # code...
                    break;
                default:
                    $query = $query->whereHas('books', function (Builder $query) {
                        $query->has('audiobook');
                    });
                    # code...
                    break;
            }
        }

        return $query;
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
