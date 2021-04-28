<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Chapter;
use App\Models\Bookmark;
use App\Models\Reaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsables\V1\ModelResponse;
use App\Http\Requests\Api\V1\Bookmarks\StoreRequest;
use App\Http\Requests\Api\V1\Bookmarks\UpdateRequest;

class BookmarkController extends Controller
{
    /**
     * Delete a Bookmark.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function delete(Request $request, Bookmark $bookmark)
    {
        $this->authorize('destroy', $bookmark);
        $bookmark->internal_updated_at = Carbon::now();
        $bookmark->delete();
    }

    /**
     * Create a bookmark.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Chapter $chapter
     *
     * @return Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Chapter $chapter)
    {
        $this->authorize('bookmark', $chapter);
        $user = $request->user();
        $count = $user->bookmarks()->where('audio_book_id', $chapter->audio_book_id)->count();

        if($request->has('to')) {
            if($request->has('title')) {
                $bookmark = new Bookmark([
                    'user_id' => $user->id,
                    'name' => $request->title,
                    'from' => $request->from,
                    'to' => $request->to,
                    'chapter_id' => $chapter->id,
                ]);
            } else {
                $bookmark = new Bookmark([
                    'user_id' => $user->id,
                    'name' => 'Signet '. ($count+1),
                    'from' => $request->from,
                    'to' => $request->to,
                    'chapter_id' => $chapter->id,
                ]);    
            }
        } else {
            if($request->has('title')) {
                $bookmark = new Bookmark([
                    'user_id' => $user->id,
                    'name' => $request->title,
                    'from' => $request->from,
                    'to' => $request->from + 30000 <= $chapter->duration ? $request->from + 30000 : $chapter->duration ,
                    'chapter_id' => $chapter->id,
                ]);
            } else {
                $bookmark = new Bookmark([
                    'user_id' => $user->id,
                    'name' => 'Signet '. ($count+1),
                    'from' => $request->from,
                    'to' => $request->from + 30000 <= $chapter->duration ? $request->from + 30000 : $chapter->duration,
                    'chapter_id' => $chapter->id,
                ]);
            }
        }
        $bookmark->internal_updated_at = Carbon::now();
        $bookmark->save();
        if($request->has('reaction')) {
            $reaction = new Reaction([
                'user_id' => $user->id,
                'feeling' => $request->reaction,
                'reactionnable_type' => Bookmark::class,
                'reactionnable_id' => $bookmark->id,
            ]);
            $reaction->save();
            $bookmark->reaction()->associate($reaction);
            $bookmark->save();
        }
        
        return new ModelResponse($bookmark, false);
    }

    /**
     * Update a bookmark.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Bookmark $bookmark
     *
     * @return Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Bookmark $bookmark)
    {
        $this->authorize('edit', $bookmark);
        
        if($request->has('title')) {
            $bookmark->name = $request->title;
        }

        if($request->has('to')) {
            $bookmark->to = $request->to;
        }

        if($request->has('from')) {
            $bookmark->from = $request->from;
        }
        
        if($request->has('reaction')) {
            $bookmark_reaction = $bookmark->reaction;
            if($bookmark_reaction != null) {
                if($bookmark_reaction->feeling != $request->reaction) {
                    $bookmark->reaction()->dissociate();
                }
            }
            $reaction = new Reaction([
                'user_id' => $request->user()->id,
                'feeling' => $request->reaction,
                'reactionnable_type' => Bookmark::class,
                'reactionnable_id' => $bookmark->id,
            ]);
            $reaction->save();
            $bookmark->reaction()->associate($reaction);
        }

        $bookmark->internal_updated_at = Carbon::now();

        $bookmark->save();

        return new ModelResponse($bookmark, false);
    }   
}