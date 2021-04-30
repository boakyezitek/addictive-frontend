<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookDetailController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.book_details');
    }
}
