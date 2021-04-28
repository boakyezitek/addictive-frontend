<?php

namespace App\Http\Controllers\Web\Admin;

use ZipArchive;
use App\Models\Chapter;
use App\Models\AudioBook;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\AudioChapterAdded;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ChapterController extends Controller
{
    public function show(AudioBook $audio_book)
    {
    	return view('admin.audiobooks.index',['audiobook' => $audio_book, 'chapter_count' => $audio_book->chapters->count()]);
    }

    public function upload(Request $request)
    {
    	$zip = new ZipArchive;
    	$audiobook = AudioBook::find($request->audiobook_id);

    	$last_chapter = $audiobook->chapters->count() > 0 ? $audiobook->unorderedChapters()->orderBy('order', 'desc')->first() : null;
    	$folder_name = substr($request->file->getClientOriginalName(), 0, -4);
    	if ($zip->open($request->file->path())) {
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
    	}
    	$zip->close();

    	return redirect('/admin/resources/audio-books/'. $audiobook->id);
    }
}
