<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConditionController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('pages.writeform.condition');
    }
}