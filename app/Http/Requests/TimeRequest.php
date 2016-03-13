<?php

namespace Batbox\Http\Requests;

use Illuminate\Contracts\Validation\Factory;
use Batbox\Models\Project;
use Illuminate\Http\Response;

class TimeRequest extends Request
{
//    public function __construct(Factory $factory)
//    {
//        parent::__construct($factory);
//
//        $validate_active_project = function ($attribute, $value, $parameters)
//        {
//            if ($project = Project::find($value))
//            {
//                if ($project->status)
//                {
//                    return true;
//                }
//            }
//            return false;
//        };
//
//        $factory->extend('project_is_active', $validate_active_project);
//    }

    public function rules()
    {
//        return [
//            'start' => ['required', 'integer', 'before:end'],
//            'end' => ['required', 'integer', 'after:start'],
//            'project_id' => ['required', 'integer', 'min:1', 'exists:projects,id'],//, 'project_is_active:'],
//            'task_id' => ['required', 'integer', 'min:1', 'exists:tasks,id'],
//        ];
    }



    public function authorize()
    {
        // Only allow logged in users
        // return \Auth::check();
        // Allows all users in
        return true;
    }

//    // OPTIONAL OVERRIDE
//    public function forbiddenResponse()
//    {
//        // Optionally, send a custom response on authorize failure
//        // (default is to just redirect to initial page with errors)
//        //
//        // Can return a response, a view, a redirect, or whatever else
////        return Response::make('Permission denied foo!', 403);
//    }
//
//    // OPTIONAL OVERRIDE
//    public function response()
//    {
//        // If you want to customize what happens on a failed validation,
//        // override this method.
//        // See what it does natively here:
//        // https://github.com/laravel/framework/blob/master/src/Illuminate/Foundation/Http/FormRequest.php
//
//
//    }
}
