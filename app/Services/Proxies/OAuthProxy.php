<?php

namespace App\Services\Proxies;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class OAuthProxy
{
    /**
     * Make a new request to social login route.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $email
     * @param  string $password
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public static function redirectToPasswordGrant(Request $request, string $email, string $password)
    {
        $request->merge([
            'grant_type' => 'password',
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'username' => $email,
            'password' => $password,
        ]);
        $request = Request::create('/oauth/token', 'POST');
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('accept', 'application/json');

        return Route::dispatch($request);
    }

    /**
     * Make a new request to refresh a token.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                  $refresh_token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public static function redirectToRefreshToken(Request $request, string $refresh_token)
    {
        $request->merge([
            'grant_type' => 'refresh_token',
            'client_id' => config('passport.client_id'),
            'client_secret' => config('passport.client_secret'),
            'refresh_token' => $refresh_token,
        ]);

        $request = Request::create('/oauth/token', 'POST');
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('accept', 'application/json');

        return Route::dispatch($request);
    }

    /**
     * Make a new request to oauth/token route.
     *
     * @param Request $request
     * @param string $provider
     * @param string $token
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public static function redirectToSocialLogin(Request $request, string $provider, string $token)
    {
        $request->merge(['token' => $token]);
        $route = route('login.social', ['provider' => $provider]);
        $request = Request::create($route, 'POST');
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('accept', 'application/json');
        $request->headers->set(config('app.installation_header'), request()->attributes->get('installation_id'));

        return Route::dispatch($request);
    }

}
