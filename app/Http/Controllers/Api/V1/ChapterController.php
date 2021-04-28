<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Chapter;
use phpseclib\Crypt\RSA;
use App\Models\AudioBook;
use Illuminate\Http\Request;
use phpseclib\Math\BigInteger;
use App\Services\Audio\WaveForm;
use App\Responsables\EmptyResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use phpseclib\Crypt\RSA as Crypt_RSA;
use App\Http\Requests\Api\V1\Chapters\DownloadRequest;
use App\Http\Requests\Api\V1\Chapters\ProgressRequest;

class ChapterController extends Controller
{
    public function wave()
    {
        $file = public_path('sample.mp3');
        $wave = new WaveForm($file);

        return $wave->getWaveformDataByPoints(100, true);
    }

    public function download(Chapter $chapter)
    {
        $this->authorize('download', $chapter);

        $transformer =  Chapter::transformer();
        
        return $transformer->downloadTransform($chapter);
    }

    public function progress(ProgressRequest $request, Chapter $chapter)
    {
        $this->authorize('progress', $chapter);
        if($request->timestamp > $chapter->duration || $request->finished == true) {
            $timestamp = $chapter->duration;
        } else {
            $timestamp = $request->timestamp;
        }
        $user = $request->user();
        $audiobook = $user->audioBooks()->where('audio_books.id', $chapter->audio_book_id)->first();
        if($audiobook->pivot) {
            if($audiobook->pivot->status != AudioBook::STATUS_IN_PROGRESS) {
                $audiobook->users()->updateExistingPivot($user->id, ['status' => AudioBook::STATUS_IN_PROGRESS]);
            }
        }
        $pivot = $chapter->users()->where('user_id', $user->id)->first();
        if($pivot) {
            $chapter->users()->updateExistingPivot($user->id, ['time_elapsed' => $timestamp]);
        } else {
            $chapter->users()->save($user, ['time_elapsed' => $timestamp]);
        }
        $previous_chapters = Chapter::where('audio_book_id', $chapter->audio_book_id)->where('order' , '<', $chapter->order)->get();
        $following_chapters = Chapter::where('audio_book_id', $chapter->audio_book_id)->where('order' , '>', $chapter->order)->get();
        foreach ($previous_chapters as $previous_chapter) {
            $pivot = $previous_chapter->users()->where('user_id', $user->id)->first();
            if($pivot) {
                if($pivot->pivot->time_elapsed < $previous_chapter->duration)
                $previous_chapter->users()->updateExistingPivot($user->id, ['time_elapsed' => $previous_chapter->duration]);
            } else {
                $previous_chapter->users()->save($user, ['time_elapsed' => $previous_chapter->duration]);
            }
        }
        foreach ($following_chapters as $following_chapter) {
            $pivot = $following_chapter->users()->where('user_id', $user->id)->first();
            if($pivot) {
                if($pivot->pivot->time_elapsed > 0) {
                    $following_chapter->users()->updateExistingPivot($user->id, ['time_elapsed' => 0]);
                }
            } else {
                $following_chapter->users()->save($user, ['time_elapsed' => 0]);
            }
        }
    }
}
