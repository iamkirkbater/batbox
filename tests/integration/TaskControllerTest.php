<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Batbox\Models\Task;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class TaskControllerTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    public function testForTaskPage()
    {
        $tasks = Task::all();
        $this->visit("/tasks")
             ->seeJsonContains(['id'=>"1"]);
    }

    public function testForSingleTask()
    {
        $taskToTest = Task::find(1);
        $this->visit("/tasks/1")
             ->seeJson([
                 'id' => "1",
             ]);
    }

    public function testForNoTaskFound()
    {
        $response = $this->call('get', '/tasks/-1');
        $this->assertEquals(HTTP::NO_CONTENT, $response->status());
    }

    public function testSuccessfullyAddNewTask()
    {
        $task = [
            "name" => "Test Task",
            "billable" => true,
        ];

        $response = $this->call('post', '/tasks', $task);
        $this->assertEquals(HTTP::CREATED, $response->status());
        $this->seeJsonContains($task);
    }

    public function testFailToAddNewTask()
    {
        $task = [];

        $response = $this->call('post', '/tasks');
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());

        $response = $this->call('post', '/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Name");
        $this->see("Billable");

        $task["name"] = "Test Task";
        $response = $this->call('post', '/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Billable");

        $task = ["billable" => true];
        $response = $this->call('post', '/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Name");

        $task["billable"] = "string";
        $task["name"] = "Test Task";
        $response = $this->call('post', '/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();

    }

    private function setupDB()
    {
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
    }

    private function prepare()
    {
        $this->setupDB();
        \Mail::pretend(true);
    }
}