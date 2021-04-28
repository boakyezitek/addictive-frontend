<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsables\V1\ModelResponse;

class ParameterController extends Controller
{
    public function index(Request $request)
    {
    	$parameter = Parameter::first();
    	return new ModelResponse($parameter, false);
    }
}
