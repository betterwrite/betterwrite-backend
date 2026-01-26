<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*'); // Or '*'
        $response->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Authorization');

        // Handle preflight OPTIONS requests
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)->withHeaders($response->headers->all());
        }

        return $response;
    }
}
