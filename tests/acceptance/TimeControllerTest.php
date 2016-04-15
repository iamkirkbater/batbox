<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Batbox\Models\Project;
use Batbox\Models\Task;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class TimeControllerTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    public function testCantAccessIndex()
    {
        $request = $this->call('get', 'api/v1/time');
        $this->assertEquals(HTTP::NOT_FOUND, $request->getStatusCode());
    }

    public function test_log_time_route_exists()
    {
        $request = $this->call('post', 'api/v1/time');
        $this->assertNotEquals(HTTP::NOT_FOUND, $request->getStatusCode());
    }

    public function test_log_time_fails_on_invalid_date()
    {
        $data = [];
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Start time is not valid.');

        $data['start'] = 'a';
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Start time is not valid.');

        $data['start'] = 1.1;
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Start time is not valid.');

        $data['start'] = strtotime('2016-02-01 13:10');
        $data['end'] = 'a';
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('End time is not valid.');

        $data['end'] = 21.12;
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('End time is not valid.');

        $data['end'] = strtotime('2016-01-31 14:00');
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('End time is before start time.');
    }

    public function test_log_time_fails_on_invalid_project()
    {
        $data = [
            'start' => strtotime('2016-02-02 09:00'),
            'end' => strtotime('2016-02-02 10:30'),
        ];
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Project not specified.');

        $data['project_id'] = null;
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Project not specified.');

        $data['project_id'] = -1;
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Invalid project id specified.');

        $data['project_id'] = 'a';
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Invalid project id specified.');
    }

    public function test_log_time_fails_on_inactive_project()
    {
        $project = $this->create_project(false);

        $data = [
            'start' => strtotime('2016-02-02 09:00'),
            'end' => strtotime('2016-02-02 10:30'),
            'project_id' => $project->id,
        ];

        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Project is not active.');
    }

    public function test_log_time_fails_on_bad_task()
    {
        $project = $this->create_project(true);

        $data = [
            'start' => strtotime('2016-02-02 09:00'),
            'end' => strtotime('2016-02-02 10:30'),
            'project_id' => $project->id,
        ];

        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see('Task not provided.');

        $data['task_id'] = -1;
        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::BAD_REQUEST, $request->getStatusCode());
        $this->see("Task not provided.");

    }

    public function test_log_time_successfully()
    {
        $project = Project::where('status', 1)->first();
        $task = Task::all()->first();
        $data = [
            'start' => strtotime('2016-02-02 09:00'),
            'end' => strtotime('2016-02-02 10:30'),
            'project_id' => $project->id,
            'task_id' => $task->id,
        ];

        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::CREATED, $request->getStatusCode());
        $this->see($data['start']);
        $this->see($data['end']);
        $this->see('"project_id":"'.$data['project_id'].'"');
        $this->see('"task_id":"'.$data['task_id'].'"');
        $this->seeInDatabase('times', $data);
    }

    public function test_time_log_with_notes()
    {
        $project = Project::where('status', 1)->first();
        $task = Task::all()->first();
        $data = [
            'start' => strtotime('2016-02-02 09:00'),
            'end' => strtotime('2016-02-02 10:30'),
            'project_id' => $project->id,
            'task_id' => $task->id,
            'notes' => 'This is my note.  IT describes what I did.'
        ];

        $request = $this->call('post', 'api/v1/time', $data);
        $this->assertEquals(HTTP::CREATED, $request->getStatusCode());
        $this->see('"notes":"'.$data['notes'].'"');
        $this->seeInDatabase('times', $data);
    }

    private function seedTestDB() {
        \Artisan::call('db:seed');
    }

    private function prepare()
    {
        \Artisan::call('migrate');
        \Mail::pretend(true);
        $this->seedTestDB();
    }

    /**
     * @return Project
     */
    private function create_project($active = true)
    {
        $project = new Project();
        $project->name = "Test Project";
        $project->status = $active;
        $project->save();
        return $project;
    }
}