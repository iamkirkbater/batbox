<?php

namespace Batbox\Http\Controllers;

use Illuminate\Http\Request;

use Batbox\Http\Requests;
use Illuminate\Http\Response;
use Batbox\Http\Controllers\Controller;
use Batbox\Models\Project as Project;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $arr = [ "projects" => Project::all() ];
        return new Response($arr, HTTP::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $project = $this->pushProject($request);

        if ($project->id)
        {
           return new Response(["project" => $project, "link" => url("/projects/".$project->id)], HTTP::CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        $arr = [$id => $project];
        return $arr;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        $project = Project::find($id);

        if ($project != null)
        {
            $updated = false;
            $updatedName = $request->get('name');
            $updatedStatus = $request->get('status');

            if (isset($updatedName))
            {
                $updated = true;
                $project->name = $request->get('name');
            }

            if (isset($updatedStatus))
            {
                $updated = true;
                $project->status = $request->get('status');
            }

            if ($updated)
            {
                $project->save();
            }

            return [
                $project->id => $project,
                "updated" => true,
            ];
        }

        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return [$id => ["deleted" => true]];
    }

    private function pushProject($request)
    {
        $project = new Project();
        $project->name = $request->get('name');
        $project->status = $request->get('status');

        $project->save();
        return $project;
    }
}
