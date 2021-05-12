<?php

namespace App\Http\Controllers\Web\Admin;

use ZipArchive;
use App\Models\Chapter;
use App\Models\AudioBook;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Events\AddMultipleChapters;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function show(AudioBook $audio_book)
    {
    	return view('admin.audiobooks.index',['audiobook' => $audio_book, 'chapter_count' => $audio_book->chapters->count()]);
    }

    public function upload(Request $request)
    {
    	$audiobook = AudioBook::find($request->audiobook_id);

        $disk = Storage::disk('gcs');

        $filename = uniqid().'_'.Carbon::now()->format('Y-m-d') .'_'. $request->file->getClientOriginalName();

        $disk->putFileAs('ZipArchives/', $request->file, $filename, 'public');

        if ($disk->exists('ZipArchives/'.$filename)) {
    	   AddMultipleChapters::dispatch($audiobook, $filename, $request->file->getClientOriginalName());
        }

    	return redirect('/admin/resources/audio-books/'. $audiobook->id);
    }
}
