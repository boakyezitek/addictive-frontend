<?php

namespace App\Http\Controllers\Api\V1;

use DateTime;
use Carbon\Carbon;
use App\Models\Chapter;
use App\Models\Bookmark;
use App\Models\Reaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SyncController extends Controller
{
    /**
     * Delete Bookmarks.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function deleteBookmarks(Request $request)
    {
    	$deleted = array();
    	$transformer =  Bookmark::transformer();
    	$not_deleted = array();
    	foreach ($request->items as $item) {
    		$bookmark = Bookmark::withTrashed()->find($item['id']);
    		if($bookmark){
	    		$this->authorize('destroy', $bookmark);
	    		if($bookmark->trashed()){
	    			array_push($deleted, $transformer->syncTransform($bookmark));
	    		} else {
                    $deleted_date = Carbon::createFromFormat(DateTime::ISO8601, $item['delete_date'])->setTimezone('UTC');
		    		if($bookmark->internal_updated_at < $deleted_date && $deleted_date > $bookmark->synchronized_at ){
		    			$bookmark->delete();
		    			array_push($deleted, $transformer->syncTransform($bookmark));
		    		} else {
		    			array_push($not_deleted, $transformer->syncTransform($bookmark));
		    		}
	    		}
    		}
    	}
    	return response()->json([
    		'deleted' => $deleted,
    		'not_deleted' => $not_deleted,
    	]);
    }

    /**
     * Create Bookmarks.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function createBookmarks(Request $request)
    {
    	$user = $request->user();
    	$sync_date = Carbon::now();
    	$items = array();
    	$transformer =  Bookmark::transformer();
    	foreach ($request->items as $item) {
            $created_date = Carbon::createFromFormat(DateTime::ISO8601, $item['created_date'])->setTimezone('UTC');
    		$chapter = Chapter::find($item['chapter_id']);
    		$this->authorize('bookmark', $chapter);
    		$count = $user->bookmarks()->where('audio_book_id', $chapter->audio_book_id)->count();
    		if(isset($item['to'])) {
	            if(isset($item['title'])) {
	                $bookmark = new Bookmark([
	                    'user_id' => $user->id,
	                    'name' => $item['title'],
	                    'from' => $item['from'],
	                    'to' => $item['to'],
	                    'chapter_id' => $chapter->id,
	                    'synchronized_at' => $sync_date,
	                    'internal_updated_at' => $created_date,
	                    'timestamp_reference' => $item['timestamp_reference'],
	                ]);
	            } else {
	                $bookmark = new Bookmark([
	                    'user_id' => $user->id,
	                    'name' => 'Signet '. ($count+1),
	                    'from' => $item['from'],
	                    'to' => $item['to'],
	                    'chapter_id' => $chapter->id,
	                    'synchronized_at' => $sync_date,
	                    'internal_updated_at' => $created_date,
	                    'timestamp_reference' => $item['timestamp_reference'],
	                ]);    
	            }
	        } else {
	            if(isset($item['title'])) {
	                $bookmark = new Bookmark([
	                    'user_id' => $user->id,
	                    'name' => $item['title'],
	                    'from' => $item['from'],
	                    'to' => $item['from'] + 30,
	                    'chapter_id' => $chapter->id,
	                    'synchronized_at' => $sync_date,
	                    'internal_updated_at' => $created_date,
	                    'timestamp_reference' => $item['timestamp_reference'],
	                ]);
	            } else {
	                $bookmark = new Bookmark([
	                    'user_id' => $user->id,
	                    'name' => 'Signet '. ($count+1),
	                    'from' => $item['from'],
	                    'to' => $item['from'] + 30,
	                    'chapter_id' => $chapter->id,
	                    'synchronized_at' => $sync_date,
	                    'internal_updated_at' => $created_date,
	                    'timestamp_reference' => $item['timestamp_reference'],
	                ]);
	            }
	        }
	        $bookmark->save();

	        if(isset($item['reaction'])) {
	            $reaction = new Reaction([
	                'user_id' => $user->id,
	                'feeling' => $item['reaction'],
	                'reactionnable_type' => Bookmark::class,
	                'reactionnable_id' => $bookmark->id,
	            ]);
	            $reaction->save();
	            $bookmark->reaction()->associate($reaction);
	            $bookmark->save();
	        }

	        $bookmarks_ids[$item['local_id']] = $bookmark->id;
	        $bookmark_created = [
				'local_id' => (int) $item['local_id'],
			];
			array_push($items, array_merge($bookmark_created, $transformer->syncTransform($bookmark)));
    	}
    	
    	return response()->json([
    		'created' => $items
    	]);
    }

    /**
     * Update Bookmarks.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function updateBookmarks(Request $request)
    {
    	$user = $request->user();
        $transformer =  Bookmark::transformer();
    	$sync_date = Carbon::now();
    	$updated = array();
    	$not_updated = array();
    	foreach ($request->items as $item) {
            $updated_date = Carbon::createFromFormat(DateTime::ISO8601, $item['updated_date'])->setTimezone('UTC');
    		$bookmark = Bookmark::withTrashed()->find($item['id']);
    		if($bookmark) {
    			$this->authorize('edit', $bookmark);
    			if($updated_date > $bookmark->internal_updated_at && $updated_date > $bookmark->synchronized_at) {
    				if($bookmark->trashed()) {
    					$bookmark->restore();
    				}

		    		if(isset($item['to'])) {
			            $bookmark->to = $item['to'];
			        }

			        if(isset($item['title'])) {
		                $bookmark->name = $item['title'];
					}
		            if(isset($item['from'])) {
			            $bookmark->from = $item['from'];
		            }

			        if(isset($item['reaction'])) {
			        	$bookmark_reaction = $bookmark->reaction;
			            if($bookmark_reaction != null) {
			                if($bookmark_reaction->feeling != $request->reaction) {
			                    $bookmark->reaction()->dissociate();
			                }
			            }

			            $reaction = new Reaction([
			                'user_id' => $request->user()->id,
			                'feeling' => $item['reaction'],
			                'reactionnable_type' => Bookmark::class,
			                'reactionnable_id' => $bookmark->id,
			            ]);
			            $reaction->save();
			            $bookmark->reaction()->associate($reaction);
			        } elseif ($item['reaction'] === null) {
                        $bookmark_reaction = $bookmark->reaction;
                        if($bookmark_reaction != null) {
                            $bookmark->reaction()->dissociate();
                        }
                    }
			        $bookmark->synchronized_at = $sync_date;
                    $bookmark->internal_updated_at = $updated_date;
			        $bookmark->save();
			        array_push($updated, $transformer->syncTransform($bookmark));
    			} else {
    				array_push($not_updated, $transformer->syncTransform($bookmark));
    			}
    		}
    	}
    	return response()->json([
    		'updated' => $updated,
    		'not_updated' => $not_updated,
    	]);
    }

    /**
     * Get Bookmarks.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function getBookmarks(Request $request)
    {
    	$user = $request->user();
    	$transformer =  Bookmark::transformer();
        $last_app_sync = Carbon::createFromFormat(DateTime::ISO8601, $request->synchronized_at)->setTimezone('UTC');
    	$bookmarksId = $user->bookmarks()->where('bookmarks.updated_at', '>', $last_app_sync)->get()->pluck('pivot.id');
    	$bookmarks = Bookmark::withTrashed()->whereIn('id', $bookmarksId)->get();
    	$created = $updated = $deleted = array();
    	foreach ($bookmarks as $bookmark) {
    		if($bookmark->trashed()){
    			array_push($deleted, $transformer->syncTransform($bookmark));
    		} elseif($bookmark->created_at > $last_app_sync){
    			array_push($created, $transformer->syncTransform($bookmark));
    		} else {
    			array_push($updated, $transformer->syncTransform($bookmark));
    		}
    	}
    	return response()->json([
    		'created' => $created,
    		'updated' => $updated,
    		'deleted' => $deleted,
    	]);
    }
}
