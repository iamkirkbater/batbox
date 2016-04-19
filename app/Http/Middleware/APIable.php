<?php

namespace Batbox\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

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
        $responseCode = $this->getResponseCodeFromData($data);
        $view = $this->getViewDisplayFromData($data);

        if ($request->wantsJson() || $view == 'json')
        {
            return new Response($data['data'], $data['responseCode']);
        }

        return view($data['view'], $data['data']);
    }

    public function getResponseCodeFromData($data)
    {
        if ( ! isset($data['responseCode']))
        {
            return HTTP::OK;
        }

        return $data['responseCode'];
    }

    public function getViewDisplayFromData($data)
    {
        if ( ! isset($data['view']))
        {
            return 'json';
        }

        return $data['view'];
    }
}