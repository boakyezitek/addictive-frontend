<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Chapter;
use Spatie\Tags\HasTags;
use App\Models\HomeSection;
use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Models\Interfaces\Transformable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Transformers\V1\AudioBookTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AudioBook extends Model implements HasMedia, Transformable
{
    use InteractsWithMedia, ManageMedia, HasTags, Eventable, HasFactory;

    const STATUS_UNREAD = 'unread';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_FAILED = 'failed';
    const STATUS_FINISHED = 'finished';

    const STATUTES = [
        self::STATUS_UNREAD,
        self::STATUS_IN_PROGRESS,
        self::STATUS_FAILED,
        self::STATUS_FINISHED,
    ];

    /** @var int */
    public const RESIZED_WIDTH = 300;

    /** @var int */
    public const RESIZED_HEIGHT = 300;

    protected $fillable = [

        'name', 'description', 'publication_date', 'view_count', 'web_duration', 'release_date', 'is_visible',
    ];

    protected $casts = [
        'publication_date' => 'datetime',
        'release_date' => 'datetime',
    ];

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order', 'asc');
    }

    public function unorderedChapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function readers()
    {
        return $this->belongsToMany(Reader::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_items')->withPivot('archived_at', 'status')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function home()
    {
        return $this->morphOne(HomeSection::class, 'homable');
    }

    public function credits()
    {
        return $this->morphMany(Credit::class, 'used');
    }

    /**
     * Get the book that owns the AudioBook
     */
    public function book()
    {
        return $this->hasOne(Book::class);
    }

    /**
     * Scope a query to only include unpublished audio books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->where('publication_date', '>', Carbon::now());
    }

    /**
     * Scope a query to only include unpublished audio books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('publication_date', '<=', Carbon::now());
    }

    /**
     * Determine if the given user possesses the audiobook
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function possessesAudioBook(User $user)
    {
        return $this->users()->where('user_id', $user->id )->count() === 1;
    }

    /**
     * Scope a query to only include audio books that match the given string.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $string)
    {
        if($string) {
            return $query->whereHas('authors', function (Builder $query) use ($string) {
                $query->where('first_name', 'like', "%{$string}%")->orWhere('last_name', 'like', "%{$string}%");
            })->orWhereHas('tags', function (Builder $query) use ($string){
                $query->where('name', 'like', "%{$string}%");
            })->orWhereHas('categories', function (Builder $query) use ($string){
                $query->where('name', 'like', "%{$string}%");
            })->orWhere('name', 'like', "%{$string}%");
        } else {
            return $query;
        }
    }

    /**
     * Scope a query to only include audio books that match given filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilters($query, $type, $orderBy, $sorting, $string = null)
    {
        if($type == 'catalog') {
            $query = $query->search($string);
        } elseif($type == 'news'){
            return $query->published()->search($string)->orderBy($orderBy, 'desc');
        } elseif($type == 'most_searched'){
            //TODO: order by visits count
            return $query->published()->search($string)->orderBy('view_count', $sorting);
        } elseif($type== 'olds'){
            return $query->published()->search($string)->orderBy($orderBy, 'asc');
        } elseif($type == 'unpublished'){
            return $query->unpublished()->search($string)->orderBy($orderBy, 'asc');
        }

        switch($orderBy) {

            case 'most_recent' :
                return $query->orderBy('publication_date', $sorting);
                break;

            case 'title' :
                return $query->orderBy('name', $sorting);
                break;

            case 'author' :
                return $query->with(['authors' => function ($query) {
                    $query->orderBy('last_name', $sorting);
                }]);
                break;

            case 'longest' :
                $query = $query->withSum('chapters as duration', 'duration')->orderBy('duration', 'desc');
                break;

            case 'shortest' :
                return $query->withSum('chapters as duration', 'duration')->orderBy('duration', 'asc');
                break;
        }

        return $query;
    }

    /**
     * Scope a query to only include audio books that match given filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLibraryFilters($query, $type, $orderBy)
    {
        if($type == self::STATUS_IN_PROGRESS){
            $query = $query->where('status', self::STATUS_IN_PROGRESS);
        } elseif($type == self::STATUS_FINISHED){
            $query = $query;
        } elseif($type == 'to_listen'){
            $query = $query->where('status', self::STATUS_UNREAD);
        } else {
            $query = $query;
        }

        switch($orderBy) {

            case 'most_recent' :
                return $query->orderBy('pivot_created_at', 'desc');
                break;

            case 'title' :
                return $query->orderBy('name', 'asc');
                break;

            case 'author' :
                return $query->with(['authors' => function ($query) {
                    $query->orderBy('last_name', 'asc');
                }]);
                break;

            case 'longest' :
                $query = $query->withSum('chapters as duration', 'duration')->orderBy('duration', 'desc');
                break;

            case 'shortest' :
                return $query->withSum('chapters as duration', 'duration')->orderBy('duration', 'asc');
                break;
        }

        return $query;
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

    public function getReadersString()
    {
        $readers = '';
        $i = 0;
        $count = count($this->readers);
        foreach($this->readers as $reader) {
            if (++$i == $count) {
                $readers .= ''.$reader->fullname;
            } else {
                $readers .= ''.$reader->fullname.', ';
            }
        }

        return $readers;
    }

    public function getDuration()
    {
        return (integer) $this->chapters()->sum('duration');
    }

    /**
     *
     * Get progression for a given User
     *
     * @param App/Models/User $user 
     *
     * @return integer Progression of the user for this book based on the most advanced chapter he listen to.
     */
    public function getProgress(User $user)
    {
        $item = $this->users()->where('user_id', $user->id)->first()->pivot;
        if ($item->status == self::STATUS_UNREAD) {
            $progression = 0;
        } elseif ($item->status == self::STATUS_FINISHED) {
            $progression = $this->getDuration();
        } elseif ($user->chapters()->where('audio_book_id', $this->id)->count() >= 1){
            $last_chapter = $user->chapters()->where('audio_book_id', $this->id)->orderBy('chapter_user.updated_at', 'desc')->first();

            $progression = $last_chapter->pivot->time_elapsed;

            $previous_chapters_duration = Chapter::where('audio_book_id', $this->id)->where('id', '<', $last_chapter->id)->sum('duration');

            $progression = $progression + $previous_chapters_duration;            
        } else {
            $progression = 0;
        }

        return $progression;

    }

    /**
     *
     * Get label for remaning time
     *
     * @param App/Models/User $user 
     *
     * @return string Label for the remaining time.
     */
    public function getRemainingTimeLabel(User $user)
    {
        $duration = $this->getDuration();
        $progression = $this->getProgress($user);
        if($duration == 0) {
            return null;
        } else {
            $carbon = Carbon::createFromTimestampMs($duration);
            if($carbon->hour >= 1){
                if($carbon->minute >= 1){
                    return $carbon->hour.' h '.$carbon->minute.' min';
                } else {
                    return $carbon->hour.' h';
                }
            } else{
                if($carbon->minute >= 1){
                    return $carbon->minute.' min';
                } else {
                    return '1 min';
                }
            }
        }

    }

    /**
     *
     * Get label for total duration
     *
     * @param App/Models/User $user
     *
     * @return string Label for the remaining time.
     */
    public function getDurationLabel()
    {
        $duration = $this->getDuration();
        if($duration == 0) {
            return null;
        } else {
            $carbon = Carbon::createFromTimestampMs($duration);
            if($carbon->hour >= 1){
                if($carbon->minute >= 1){
                    return $carbon->hour.' h '.$carbon->minute.' min';
                } else {
                    return $carbon->hour.' h';
                }
            } else{
                if($carbon->minute >= 1){
                    return $carbon->minute.' min';
                } else {
                    return '1 min';
                }
            }
        }

    }

    public function maskedStatus(User $user)
    {
        if($user->audioBooks()->where('audio_book_id', $this->id )->count() === 1) {
            $user_book = $user->audioBooks()->where('audio_book_id', $this->id)->first()->pivot;
            if($user_book->archived_at){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function transformer() : TransformerAbstract
    {
        return new AudioBookTransformer();
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/covers')
            ->useDisk('gcs')
            ->singleFile();

        $this
            ->addMediaCollection($this->table.'/extracts')
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
