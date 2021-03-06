<?php

namespace Batbox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Teapot\HttpResponse\Status\StatusCode as HTTP;
use Batbox\Http\Requests;
use Batbox\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tasks = Task::all();
        return ['tasks' => $tasks];
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
        $name_not_provided = !$request->get("name");
        $billable_not_provided = !$request->get("billable");

        if ( $name_not_provided || $billable_not_provided ) {
            $param = "";
            $param .= ($name_not_provided) ? "Name" : "";
            $param .= ($name_not_provided && $billable_not_provided) ? ", " : "";
            $param .= ($billable_not_provided) ? "Billable" : "";
            return new Response(["error" => true, "message" => "$param parameter(s) not provided."], HTTP::BAD_REQUEST);
        }

        $name = filter_var($request->get("name"), FILTER_SANITIZE_STRING);
        $billable = filter_var($request->get("billable"), FILTER_VALIDATE_BOOLEAN, ['options' => ['default' => null]]);

        $task = new Task();

        if ($name && $billable)
        {
            $task->name = $name;
            $task->billable = $billable;
            $task->save();
        }

        if ($task->id)
        {
            return new Response($task, HTTP::CREATED);
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
        $task = Task::find($id) or null;
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
        $task = Task::find($id);

        if ($task == null)
        {
            return new Response("", HTTP::NOT_MODIFIED);
        }

        $updated = false;
        $updatedName = $request->get('name');
        $updatedBillableFlag = $request->get('billable');

        if (isset($updatedName))
        {
            $updated = true;
            $name = filter_var($request->get("name"), FILTER_SANITIZE_STRING);
            $task->name = $name;
        }

        if (isset($updatedBillableFlag))
        {
            if ( ! is_bool($request->get('billable')))
            {
                return new Response(["error"=>true, "message"=>"Invalid Billable Argument"], HTTP::BAD_REQUEST);
            }
            $updated = true;
            $task->billable = $request->get('billable');
        }

        if ($updated)
        {
            $task->save();
            return [
                $task->id => $task,
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
        $task = Task::destroy($id);

        if ($task === 1) {
            return new Response(["deleted" => true, "id" => $id]);
        }

        return new Response("", HTTP::NOT_MODIFIED);
    }
}
