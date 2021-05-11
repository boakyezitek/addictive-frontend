<?php

namespace App\Listeners\Chapters;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Chapter;
use App\Events\AudioChapterAdded;
use App\Events\AddMultipleChapters;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MultipleChaptersAdded implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  AudioChapterAdded  $event
     * @return void
     */
    public function handle(AddMultipleChapters $event)
    {
        $zip = new ZipArchive;
        $audiobook = $event->audiobook;
        $disk = Storage::disk('gcs');

        if ($disk->exists('ZipArchives/'.$event->filename)) {
            $file = $disk->url('ZipArchives/'.$event->filename);
            $local_file = Storage::disk('local')->putFileAs('ZipArchives', $file, $event->filename);
            $last_chapter = $audiobook->chapters->count() > 0 ? $audiobook->unorderedChapters()->orderBy('order', 'desc')->first() : null;
            $folder_name = substr($event->original_filename, 0, -4);
            $path = Storage::disk('local')->path($local_file);
            if ($zip->open($path, ZipArchive::CREATE)) {
                $indexes = [];
                for($i = 1; $i < $zip->numFiles; $i++) {
                    if(substr($zip->getNameIndex($i), -3) == "mp3") {
                        array_push($indexes, $zip->getNameIndex($i));
                        sort($indexes, SORT_REGULAR);
                    }
                }
                $order = $last_chapter ? $last_chapter->order + 1 : 1;
                foreach ($indexes as $index) {
                    $fp = $zip->getStream($index);
                    $explode = explode("/", $index);
                    $name = substr($explode[1], 0, -4);
                    $chapter = new Chapter([
                        'name' => 'Chapitre '.$order,
                        'order' => $order,
                    ]);

                    $audiobook->chapters()->save($chapter);

                    $contents = stream_get_contents($fp);
                    
                    $chapter->addMediaFromBase64(base64_encode($contents))->usingFileName(Carbon::now()."-".$name.'.mp3')->toMediaCollection('chapters/audio');

                    fclose($fp);

                    AudioChapterAdded::dispatch($chapter);

                    $order += 1;
                }
            $zip->close();
            Storage::disk('local')->delete($local_file);
            }
        $disk->delete('ZipArchives/'.$event->filename);
        }
    }
}
