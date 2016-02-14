<?php

namespace Batbox\Validators;

use Illuminate\Validation\Factory as ValidationFactory;
use Batbox\Models\Project;

class TimeValidator extends Validator {

    public function __construct( ValidationFactory $validator )
    {
        parent::__construct($validator);

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

        $this->validator->extend('project_is_active', $validate_active_project);
    }

    public $rules = [
        'start' => ['required', 'integer', 'before:end'],
        'end' => ['required', 'integer', 'after:start'],
        'project_id' => ['required', 'integer', 'min:1', 'exists:projects,id', 'project_is_active:'],
        'task_id' => ['required', 'integer', 'min:1', 'exists:tasks,id'],
    ];

    public $errors =  [
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