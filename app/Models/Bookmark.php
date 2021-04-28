<?php

namespace App\Models;

use App\Models\Traits\Eventable;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use App\Transformers\V1\BookmarkTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Bookmark extends Model
{
    use Eventable;
    use SoftDeletes;

    protected $fillable = ['name', 'chapter_id', 'user_id', 'reaction_id', 'from', 'to', 'timestamp_reference', 'internal_updated_at', 'synchronized_at'];

    protected $dates = ['deleted_at', 'synchronized_at', 'internal_updated_at'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reaction()
    {
        return $this->belongsTo(Reaction::class);
    }

    public static function transformer() : TransformerAbstract
    {
        return new BookmarkTransformer();
    }

    public function getDuration()
    {
        return $this->to - $this->from;
    }

    public function getLabel()
    {
        $duration = $this->getDuration();
        return date("i:s", $duration);
    }
}
