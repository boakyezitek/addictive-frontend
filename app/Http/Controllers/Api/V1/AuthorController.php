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
}
