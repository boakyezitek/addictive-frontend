<?php

namespace App\Models;

use App\Models\User;
use App\Helpers\FileHelper;
use App\Models\Traits\Eventable;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\ManageMedia;
use Illuminate\Support\Facades\Log;
use phpseclib\Crypt\RSA as Crypt_RSA;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Spatie\EloquentSortable\SortableTrait;
use App\Transformers\V1\ChapterTransformer;
use Spatie\MediaLibrary\InteractsWithMedia;

class Chapter extends Model implements HasMedia
{

    use InteractsWithMedia, ManageMedia, SortableTrait, Eventable;

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    protected $fillable = [
      'name', 'order',
    ];

    public function audioBook()
    {
        return $this->belongsTo(AudioBook::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chapter_user')->withPivot('time_elapsed')->withTimestamps();
    }

    public static function transformer() : TransformerAbstract
    {
        return new ChapterTransformer();
    }

    public function getDownload()
    {
        $file = $this->getUploadedMedia('audio');
        // $file = File::get(public_path('sample.mp3'));
        if($file) {
            return base64_encode(file_get_contents($file->getUrl()));
        } else {
            return null;
        }
    }

    public function registerMediaCollections() : void
    {
        $this
            ->addMediaCollection($this->table.'/audio')
            ->useDisk('gcs')
            ->singleFile();
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
        $chapter = $user->chapters()->where('chapter_user.chapter_id', $this->id)->first();

        $progression = $chapter->pivot->time_elapsed;

        return $progression;
    }
}
