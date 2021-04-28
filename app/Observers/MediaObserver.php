<?php

namespace App\Observers;

use FFMpeg\FFProbe;
use Barryvdh\Debugbar\Facade as DebugBar;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaObserver
{
    public function created(Media $media) {
        //$media->model->touch();
    }

    public function updated(Media $media) {
        if(!$media->hasCustomProperty('height') && $media->collection_name != "bonus/videos"){
            list($width, $height) = getimagesize($media->getUrl());
            $media->setCustomProperty('height', $height);
            $media->setCustomProperty('width', $width);
            $media->save();
        }
        
        if($media->collection_name == "chapters/audio"){
            $ffmpeg = FFProbe::create();
            $audio = $ffmpeg->format($media->getUrl());
            $duration = $audio->get('duration');
            $model = $media->model;
            $model->duration = $duration*1000; //miliseconds
            $model->save();
        }
    }

    public function deleted(Media $media){
        //$media->model->touch();
    }
}
