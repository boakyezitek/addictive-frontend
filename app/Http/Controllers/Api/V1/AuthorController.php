<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsables\V1\ModelResponse;

class AuthorController extends Controller
{
    /**
     * Get audio book detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Responsables\V1\UserResponse
     */
    public function show(Request $request, Author $author)
    {
        return new ModelResponse($author, false);
    }

    /**
     * Get WEB Author detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Responsables\V1\UserResponse
     */
    public function index(Request $request, Author $author)
    {
        return new ModelResponse($author, false);
    }

    /**
     * Get all WEB Authors
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Responsables\V1\UserResponse
     */
    public function all(Request $request)
    {
        $letter = $request->has('letter') ? $request->letter : null;
        $string = $request->has('q') ? $request->q : null;
        $format = $request->has('format') ? $request->format : null;

        $authors = Author::where('is_web', 1)->filters($letter, $string, $format)->orderBy('first_name', 'asc');
        return new ModelResponse($authors, true, 30);
    }
}
