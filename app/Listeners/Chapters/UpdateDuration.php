<?php

namespace App\Listeners\Chapters;

use FFMpeg\FFProbe;
use App\Models\Chapter;
use App\Events\AudioChapterAdded;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateDuration
{
    /**
     * Handle the event.
     *
     * @param  AudioChapterAdded  $event
     * @return void
     */
    public function handle(AudioChapterAdded $event)
    {
        $chapter = $event->chapter;
        
        $media = $chapter->getMedia('chapters/audio')->first();

        $ffmpeg = FFProbe::create();
        $audio = $ffmpeg->format($media->getUrl());
        $duration = $audio->get('duration');

        $chapter->duration = $duration*1000; //miliseconds
        $chapter->save();
    }
}
