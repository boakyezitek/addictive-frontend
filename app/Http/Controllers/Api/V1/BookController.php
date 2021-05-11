<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsables\V1\ModelResponse;

class BookController extends Controller
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
        if ($request->has('type')){
            $type = $request->type;
        }else {
            $type = 'catalog';
        }
        if ($request->has('sorting')){
            $sorting = $request->sorting;
        }else {
            $sorting = 'asc';
        }
        if ($request->has('order_by')){
            $orderBy = $request->order_by;
        }else {
            $orderBy = 'most_recent';
        }
        if ($request->has('q')){
            $string = $request->q;
        }else {
            $string = null;
        }
        if ($request->has('limit')){
            $limit = $request->limit;
        }else {
            $limit = null;
        }

        $books = Book::filters($type, $orderBy, $sorting, $string)->when($limit, function ($query, $limit) {
                return $query->take($limit);
            });

        if ($books->count() >= 1) {
            if ($limit) {
                return new HomeResponse($books, true); //Use a non paginated response
            } else {
                return new ModelResponse($books, true);
            }
        }else {
            return response(null, 204);
        }

    }

    /**
     * Get book detail
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $audioBook
     *
     * @return \App\Responsables\V1\ModelResponse
     */
    public function show(Request $request, Book $book)
    {
        return new ModelResponse($book, false);
    }
}
