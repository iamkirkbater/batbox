<?php

namespace Batbox\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

abstract class Request extends FormRequest
{
    public function wantsJson()
    {
        return true;
    }

    public function response(array $errors)
    {
        return new Response(json_encode(['error' => true, 'messages' => $errors]), HTTP::BAD_REQUEST);
    }
}
