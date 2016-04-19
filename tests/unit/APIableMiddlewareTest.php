<?php

use Batbox\Http\Middleware\APIable;
use Illuminate\Http\Request;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class APIableMiddlewareTest extends \TestCase {

    public function setUp()
    {
        parent::setUp();
    }

    public function test_that_correct_response_is_returned()
    {
        $request = new Illuminate\Http\Request();
        $middleware = new APIable();

        $this->assertEquals(HTTP::OK, $middleware->getResponseCodeFromData([]));
        $this->assertEquals(HTTP::BAD_REQUEST, $middleware->getResponseCodeFromData(['responseCode' => HTTP::BAD_REQUEST]));
    }

    public function test_that_correct_view_is_returned()
    {
        $request = new Illuminate\Http\Request();
        $middleware = new APIable();

        $this->assertEquals('json', $middleware->getViewDisplayFromData([]));
        $this->assertEquals("dashboard", $middleware->getViewDisplayFromData(['view' => "dashboard"]));
    }

}
