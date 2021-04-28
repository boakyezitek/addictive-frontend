<?php

namespace App\Console\Commands\Chapters;

use FFMpeg\FFProbe;
use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EnsureDurationMilliseconds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chapter:duration-ms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure the duration of the chapter is in ms.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $medias = Media::where('collection_name', 'chapters/audio')->get();

        foreach ($medias as $media) {
            $ffmpeg = FFProbe::create();
            $audio = $ffmpeg->format($media->getUrl());
            $duration = $audio->get('duration');
            $model = $media->model;
            $model->duration = $duration*1000; //miliseconds
            $model->save();
        }
    }
}
