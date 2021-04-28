<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Installations\InstallationStoreRequest;
use App\Http\Requests\Api\V1\Installations\InstallationUpdateRequest;
use App\Models\Installation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstallationController extends Controller
{
    /**
     * Create a new installation.
     *
     * @param  \App\Http\Requests\Api\V1\Installations\InstallationStoreRequest $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(InstallationStoreRequest $request)
    {
        $installation = new Installation;
        $installation->fill($request->validated());

        DB::transaction(function () use ($installation) {
            if ($user = Auth::user()) {
                $installation->user()->associate($user);
            }
            $installation->save();
        });

        return response()->json([
            'data' => [
                'id' => $installation->uuid,
            ]
        ], 201);
    }

    /**
     * Update an installation
     *
     * @param  \App\Http\Requests\Api\V1\Installations\InstallationStoreRequest $request
     * @param  string       $uuid
     *
     * @return Illuminate\Http\Response
     */
    public function update(InstallationUpdateRequest $request, $uuid)
    {
        $installation = Installation::whereUuid($uuid)->first();
        $installation->fill($request->validated());

        DB::transaction(function () use ($installation) {
            if ($user = Auth::user()) {
                $installation->user()->associate($user);
            }
            $installation->save();
        });

        return response()->json([
            'data' => [
                'id' => $installation->uuid,
            ]
        ], 201);
    }
}
