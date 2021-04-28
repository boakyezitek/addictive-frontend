<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
    	return view('pages.home', ['email' => $request->mail]);
    	// return view('email.verified', ['email' => $request->mail]);
    }
}
