<?php

namespace App\Http\Middleware;

use App\Models\Installation;
use Auth;
use Closure;

class CheckRevenueCat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        abort_if($token != config('revenue-cat.header'), 403, 'You must provide a valid  header.');

        return $next($request);
    }
}
