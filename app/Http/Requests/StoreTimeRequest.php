<?php

namespace Batbox\Http\Requests;

use Batbox\Models\Project;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Factory;

class StoreTimeRequest extends Request
{
    public function __construct(Factory $factory)
    {
        $validate_active_project = function ($attribute, $value, $parameters)
        {
            if ($project = Project::find($value))
            {
                if ($project->status)
                {
                    return true;
                }
            }
            return false;
        };

        $factory->extend('project_is_active', $validate_active_project);
    }

    /**
     * Overrides parent function to append custom validation.
     *
     * @return Validator
//     */
//    public function getValidatorInstance()
//    {
//        $validator = parent::getValidatorInstance();
//
//
//
//        return $validator;
//    }

//    public function validator(Factory $factory)
//    {
//
//
//        return $this;
//    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start' => ['required', 'integer', 'before:end'],
            'end' => ['required', 'integer', 'after:start'],
            'project_id' => ['required', 'integer', 'min:1', 'exists:projects,id', 'project_is_active'],
            'task_id' => ['required', 'integer', 'min:1', 'exists:tasks,id'],
        ];
    }

    public function messages()
    {
        return [
            'start.required' => 'Start time is not valid.',
            'start.integer' => 'Start time is not valid.',
            'start.before' => 'End time is before start time.',
            'end.required' => 'End time is not valid.',
            'end.integer' => 'End time is not valid.',
            'end.after' => 'End time is before start time.',
            'project_id.required' => 'Project not specified.',
            'project_id.integer' => 'Invalid project id specified.',
            'project_id.min' => 'Invalid project id specified.',
            'project_id.exists' => 'Invalid project id specified.',
            'project_id.project_is_active' => 'Project is not active.',
            'task_id.required' => 'Task not provided.',
            'task_id.min' => 'Task not provided.',
            'task_id.integer' => 'Task not found.',
            'task_id.exists' => 'Task not found.',
        ];
    }
}
