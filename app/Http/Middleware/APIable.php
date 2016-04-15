<?php

namespace Batbox\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class APIable
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data = $next($request)->getOriginalContent();

        if ($request->wantsJson())
        {
            if ( ! isset($data['responseCode']))
            {
                $data['responseCode'] = 200;
            }
            return new Response($data['data'], $data['responseCode']);
        }

        return view($data['view'], $data['data']);
    }
}