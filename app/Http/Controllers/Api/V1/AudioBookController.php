<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\AudioBook;
use Illuminate\Http\Request;
use App\Responsables\EmptyResponse;
use App\Http\Controllers\Controller;
use App\Responsables\V1\HomeResponse;
use App\Responsables\V1\ModelResponse;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Api\V1\AudioBooks\SearchRequest;

class AudioBookController extends Controller
{
    /**
     * Retrieve all AudioBooks based on filters.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('type')){
            $type = $request->type;
        }else {
            $type = 'catalog';
        }
        if($request->has('sorting')){
            $sorting = $request->sorting;
        }else {
            $sorting = 'asc';
        }
        if($request->has('order_by')){
            $orderBy = $request->order_by;
        }else {
            $orderBy = 'publication_date';
        }
        if($request->has('q')){
            $string = $request->q;
        }else {
            $string = null;
        }
        if($request->has('limit')){
            $limit = $request->limit;
        }else {
            $limit = null;
        }

        $audioBooks = AudioBook::filters($type, $orderBy, $sorting, $string)->when($limit, function ($query, $limit) {
                return $query->take($limit);
            });

        if($audioBooks->count() >= 1) {
            if($limit) {
                return new HomeResponse($audioBooks, true); //Use a non paginated response
            } else {
                return new ModelResponse($audioBooks, true);
            }
        }else {
            return response(null, 204);
        }

    }

    /**
     * Get audio book detail
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return \App\Responsables\V1\ModelResponse
     */
    public function show(Request $request, AudioBook $audioBook)
    {
        $audioBook->view_count += 1;
        $audioBook->save();
        return new ModelResponse($audioBook, false);
    }

    /**
     * Get audio book extract
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     * 
     * @return \App\Transformers\V1\AudioBookTransformer
     */
    public function extract(AudioBook $audioBook)
    {
        $transformer =  AudioBook::transformer();
        return $transformer->extractTransform($audioBook);
    }

    /**
     * Mask an owned audioBook
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return Illuminate\Http\Response
     */
    public function mask(Request $request, AudioBook $audioBook)
    {
        $this->authorize('mask', $audioBook);
        $user = $request->user();
        $pivot = $user->audioBooks()->where('audio_book_id', $audioBook->id)->first()->pivot;
        if($pivot->archived_at == null) {
            $user->AudioBooks()->updateExistingPivot($audioBook->id, ['archived_at' => Carbon::now()]);
        }
    }

    /**
     * Unmask an owned audioBook
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return Illuminate\Http\Response
     */
    public function unmask(Request $request, AudioBook $audioBook)
    {
        $this->authorize('mask', $audioBook);
        $user = $request->user();
        $pivot = $user->audioBooks()->where('audio_book_id', $audioBook->id)->first()->pivot;
        if($pivot->archived_at != null) {
            $user->AudioBooks()->updateExistingPivot($audioBook->id, ['archived_at' => null]);
        }
    }

    /**
     * Mark as Read an owned audioBook
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return Illuminate\Http\Response
     */
    public function markAsRead(Request $request, AudioBook $audioBook)
    {
        $this->authorize('mark', $audioBook);
        $user = $request->user();
        $user->AudioBooks()->updateExistingPivot($audioBook->id, ['status' => $audioBook::STATUS_FINISHED]);
        $user->completeAudioBook($audioBook);
    }

    /**
     * Mark as Unread an owned audioBook
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return Illuminate\Http\Response
     */
    public function markAsUnread(Request $request, AudioBook $audioBook)
    {
        $this->authorize('mark', $audioBook);
        $user = $request->user();
        $user->AudioBooks()->updateExistingPivot($audioBook->id, ['status' => $audioBook::STATUS_UNREAD]);
        $user->resetAudioBook($audioBook);
    }

    /**
     * Buy an audioBook
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return Illuminate\Http\Response
     */
    public function purchase(Request $request, AudioBook $audioBook)
    {
        $this->authorize('purchase', $audioBook);
        $user = $request->user();
        $user->audioBooks()->attach($audioBook->id, [
            'status' => AudioBook::STATUS_UNREAD,
            'archived_at' => null,
        ]);
        $credit = $user->getExpiringCredit();

        $credit->used_at = Carbon::now();

        $credit->used()->associate($audioBook);

        $credit->save();
    }
}