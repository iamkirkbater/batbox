<?php

namespace Batbox\Http\Controllers;

use Batbox\Exceptions\ValidationException;
use Batbox\Http\Requests;
use Batbox\Models\Project;
use Batbox\Models\Task;
use Batbox\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Teapot\HttpResponse\Status\StatusCode as HTTP;
use \Input;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Response('', HTTP::NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreTimeRequest $request)
    {
        $time = new Time();
        $time->start = Input::get('start');
        $time->end = Input::get('end');
        $time->project_id = Input::get('project_id');
        $time->task_id = Input::get('task_id');
        $time->notes = Input::get('notes');
        $time->save();

        return new Response($time, HTTP::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
