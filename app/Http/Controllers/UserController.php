<?php

namespace Batbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Teapot\HttpResponse\Status\StatusCode as HTTP;
use Batbox\Http\Requests;
use Batbox\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();
        return ['users' => $users];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return new Response("", HTTP::METHOD_NOT_ALLOWED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $first_not_provided = !$request->get("first");
        $last_not_provided = !$request->get("last");
        $username_not_provided = !$request->get("username");

        if ( $first_not_provided || $last_not_provided || $username_not_provided) {
            $param = "";
            $param .= ($first_not_provided) ? "First Name" : "";
            $param .= ($first_not_provided && $last_not_provided) ? ", " : "";
            $param .= ($last_not_provided) ? "Last Name" : "";
            $param .= ($last_not_provided && $username_not_provided) ? ", " : "";
            $param .= ($username_not_provided) ? "Username" : "";
            return new Response(["error" => true, "message" => "$param parameter(s) not provided."], HTTP::BAD_REQUEST);
        }

        $first = filter_var($request->get("first"), FILTER_SANITIZE_STRING);
        $last = filter_var($request->get("last"), FILTER_SANITIZE_STRING);
        $username = filter_var($request->get("username"), FILTER_SANITIZE_STRING);

        $user = new User();

        if ($first && $last && $username)
        {
            $user->first = $first;
            $user->last = $last;
            $user->username = $username;
            $user->save();
        }

        if ($user->id)
        {
            return new Response($user, HTTP::CREATED);
        }

        return new Response(["error" => true, "message" => "All paramaters were provdied, but one or more parameters was not valid."], HTTP::BAD_REQUEST);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $task = User::find($id) or null;
        if ($task)
        {
            return [$id => $task];
        }
        return new Response("", HTTP::NO_CONTENT);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return new Response("", HTTP::METHOD_NOT_ALLOWED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null)
        {
            return new Response("", HTTP::NOT_MODIFIED);
        }

        $updated = false;
        $updatedFirst = $request->get('first');
        $updatedLast = $request->get('last');
        $updatedUsername = $request->get('username');

        if (isset($updatedFirst))
        {
            $updated = true;
            $first = filter_var($request->get("first"), FILTER_SANITIZE_STRING);
            $user->first = $first;
        }
        if (isset($updatedLast))
        {
            $updated = true;
            $last = filter_var($request->get("last"), FILTER_SANITIZE_STRING);
            $user->last = $last;
        }
        if (isset($updatedUsername))
        {
            $updated = true;
            $username = filter_var($request->get("username"), FILTER_SANITIZE_STRING);
            $user->username = $username;
        }

        if ($updated)
        {
            $user->save();
            return [
                $user->id => $user,
                "updated" => true,
            ];
        }

        return new Response("", HTTP::NOT_MODIFIED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $temp = User::find($id);
        $user = User::destroy($id);

        if ($user === 1) {
            return new Response(["deleted" => true, "id" => $id]);
        }

        return new Response("", HTTP::NOT_MODIFIED);
    }
}
