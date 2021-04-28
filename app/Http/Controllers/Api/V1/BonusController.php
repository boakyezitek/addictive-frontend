<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bonus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsables\V1\ModelResponse;

class BonusController extends Controller
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
        return new ModelResponse(Bonus::orderBy('created_at', 'desc'), true);
    }

    /**
     * Get bonus audio
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $bonus
     * 
     * @return \App\Transformers\V1\AudioBookTransformer
     */
    public function audio(Bonus $bonus)
    {
        $this->authorize('audio', $bonus);
        $transformer =  Bonus::transformer();
        return $transformer->audioTransform($bonus);
    }

    /**
     * Get bonus video
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AudioBook $bonus
     * 
     * @return \App\Transformers\V1\AudioBookTransformer
     */
    public function video(Bonus $bonus)
    {
        $this->authorize('video', $bonus);
        $transformer =  Bonus::transformer();
        return $transformer->videoTransform($bonus);
    }

    /**
     * Get audio book detail
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Responsables\V1\UserResponse
     */
    public function show (Request $request, Bonus $bonus)
    {
        $this->authorize('show', $bonus);
        return new ModelResponse($bonus, false);
    }
}