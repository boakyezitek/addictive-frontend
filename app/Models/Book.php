<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Tags\HasTags;
use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use App\Transformers\V1\BookTransformer;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia, Transformable
{
    use InteractsWithMedia, ManageMedia, HasTags, Eventable, HasFactory;

     /** @var int */
    public const RESIZED_WIDTH = 300;

    /** @var int */
    public const RESIZED_HEIGHT = 300;

    protected $fillable = [
        'edisource_id', 'title', 'description', 'ebook_id', 'print_id', 'audio_book_id', 'parent_id', 'child_id', 'release_date',
    ];

    protected $append = ['children_count', 'parent_count', 'position', 'volume_amount'];

    protected $casts = [
        'release_date' => 'datetime'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function ebook()
    {
    	return $this->belongsTo(Ebook::class);
    }

    public function print()
    {
    	return $this->belongsTo(PrintBook::class);
    }

    public function audiobook()
    {
    	return $this->belongsTo(AudioBook::class, 'audio_book_id');
    }

    public function children()
    {
        return $this->hasOne(static::class, 'parent_id');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }

    public function getChildrenCountAttribute()
    {
        $count = 0;
        $book = $this;
        if ($book->children) {
            do {
                $count += 1;
                $book = $book->children;
            }
            while ($book->children);
        }
        return $count;
    }

    public function getParentCountAttribute()
    {
        $count = 0;
        if ($this->parent) {
            $book = $this;
            do {
                $count += 1;
                $book = $book->parent;
            }
            while ($book->parent);
        }
        return $count;

    }

    public function getPositionAttribute()
    {
        return $this->parent_count + 1;
    }

    public function getVolumeAmountAttribute()
    {
        return $this->parent_count + $this->children_count + 1;
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getAuthorsString()
    {
        $authors = '';
        $i = 0;
        $count = count($this->authors);
        foreach($this->authors as $author) {
            if (++$i == $count) {
                $authors .= ''.$author->fullname;
            } else {
                $authors .= ''.$author->fullname.', ';
            }
        }

        return $authors;
    }

    public function getVolumeString()
    {
        return $this->position.'/'.$this->volume_amount;
    }

    /**
     * Scope a query to only include unpublished audio books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->where('release_date', '>', Carbon::now());
    }

    /**
     * Scope a query to only include unpublished audio books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('release_date', '<=', Carbon::now());
    }

    /**
     * Scope a query to only include books that match given filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, $type, $orderBy, $sorting, $string = null)
    {
        if($type == 'catalog') {
            $query = $query->search($string);
        } elseif($type == 'news'){
            return $query->search($string)->published()->orderBy('release_date', 'desc');
        } elseif($type == 'unpublished'){
            return $query->search($string)->unpublished()->orderBy('release_date', 'asc');
        }

        switch($orderBy) {

            case 'most_recent' :
                return $query->orderBy('release_date', $sorting);
                break;

            case 'title' :
                return $query->orderBy('title', $sorting);
                break;

            case 'author' :
                return $query->with(['authors' => function ($query) {
                    $query->orderBy('last_name', $sorting);
                }]);
                break;
        }

        return $query;
    }

    /**
     * Scope a query to only include books that match the given string.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $string)
    {
        if($string) {
            return $query->whereHas('authors', function (Builder $query) use ($string) {
                $query->where('first_name', 'like', "%{$string}%")->orWhere('last_name', 'like', "%{$string}%");
            })->orWhereHas('categories', function (Builder $query) use ($string){
                $query->where('name', 'like', "%{$string}%");
            })->orWhere('title', 'like', "%{$string}%");
        } else {
            return $query;
        }
    }

    public static function transformer() : TransformerAbstract
    {
        return new BookTransformer();
    }

    public function registerMediaCollections() : void
    {
        $this->addMediaCollection($this->table.'/covers')
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
