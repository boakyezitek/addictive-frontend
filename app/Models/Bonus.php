<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\BonusTransformer;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Bonus extends Model implements HasMedia
{

    use InteractsWithMedia, ManageMedia, Eventable;

    /** @var int */
    public const RESIZED_WIDTH = 300;

    /** @var int */
    public const RESIZED_HEIGHT = 300;

    protected $table = 'bonus';

    protected $fillable = ['name', 'subtitle', 'introduction', 'sections', 'audio_book_id'];

    protected $casts = [
        'sections' => 'collection',
    ];

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/covers')
            ->useDisk('gcs')
            ->singleFile();

        $this
            ->addMediaCollection($this->table.'/audios')
            ->useDisk('gcs')
            ->singleFile();

        $this
            ->addMediaCollection($this->table.'/videos')
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

    public static function transformer() : TransformerAbstract
    {
        return new BonusTransformer();
    }

    public function formatDescription()
    {
        $sections = [];
        foreach(json_decode($this->sections) as $key => $section){
            $item = ['title' => $key, 'content' => $section];
            array_push($sections, $item);
        }
        return $sections;
    }

    /**
     *
     * Allow you to know if current user unlocked the bonus. 
     *
     *
     * @return bool true if the bonus is locked, false is the bonus is unlocked.
     */
    public function lockedStatus()
    {
        $user = Auth::user();
        return $user->audioBooks()->where('audio_book_id', $this->audio_book_id)->count() === 0;
    }
}
