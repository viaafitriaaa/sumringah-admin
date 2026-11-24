<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerDemoOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(app()->environment(['local', 'testing']), 404);

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, private');

        return $response;
    }
}
