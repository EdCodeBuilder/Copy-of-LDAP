<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Signature
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set( 'X-Application-SIM-IDRD', config('app.name') );
        return $response;
    }
}
