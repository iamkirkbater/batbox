<?php

namespace Batbox\Http\Controllers;

use Illuminate\Http\Request;

use Batbox\Http\Requests;
use Batbox\Http\Controllers\Controller;
use Batbox\Models\Project;

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
        return $arr;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $project = new Project();

        return [
            $project->id => $this->pushProject($request, $project),
            "created" => true,
        ];
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
            return [
                $project->id => $this->pushProject($request, $project),
                "updated" => true,
            ];
        }

        return null;
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

    private function pushProject($request, $project)
    {
        $project->name = $request->get('name');
        $project->status = $request->get('status');
        // TODO: Add client/contact update

        $project->save();
        return $project;
    }
}
