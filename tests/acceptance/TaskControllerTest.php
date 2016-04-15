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
        $this->visit("api/v1/tasks")
             ->seeJsonContains(['id'=>"1"]);
    }

    public function testForSingleTask()
    {
        $taskToTest = Task::find(1);
        $this->visit("api/v1/tasks/1")
             ->seeJson([
                 'id' => "1",
             ]);
    }

    public function testForNoTaskFound()
    {
        $response = $this->call('get', 'api/v1/tasks/-1');
        $this->assertEquals(HTTP::NO_CONTENT, $response->status());
    }

    public function testSucrcessfullyAddNewTask()
    {
        $task = [
            "name" => "Test Task",
            "billable" => true,
        ];

        $response = $this->call('post', 'api/v1/tasks', $task);
        $this->assertEquals(HTTP::CREATED, $response->status());
        $this->seeJsonContains($task);
    }

    public function testFailToAddNewTask()
    {
        $task = [];

        $response = $this->call('post', 'api/v1/tasks');
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());

        $response = $this->call('post', 'api/v1/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Name");
        $this->see("Billable");

        $task["name"] = "Test Task";
        $response = $this->call('post', 'api/v1/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Billable");

        $task = ["billable" => true];
        $response = $this->call('post', 'api/v1/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Name");

        $task["billable"] = "string";
        $task["name"] = "Test Task";
        $response = $this->call('post', 'api/v1/tasks', $task);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
    }

    public function test_patch_an_existing_task()
    {
        // Create a test task so we can change it, then verify that it's there.
        $testTask = $this->generateTestTask();
        $testTask->save();
        $this->seeInDatabase('tasks', ['name'=>$testTask->name]);

        $task = [
            "name" => "Updated Task",
        ];
        $response = $this->call('patch', 'api/v1/tasks/'.$testTask->id, $task);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeJsonContains(array("name" => "Updated Task"));

        $testTask->save();

        $task = [
            "billable" => false,
        ];
        $response = $this->call('patch', 'api/v1/tasks/' . $testTask->id, $task);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeJsonContains(["billable" => false]);
    }

    public function test_fail_patch_tasks()
    {
        $response = $this->call('patch', 'api/v1/tasks/-1', []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());

        $testTask = $this->generateTestTask();
        $testTask->save();
        $this->seeInDatabase('tasks', ['name'=>$testTask->name]);

        $response = $this->call('patch', 'api/v1/tasks/'.$testTask->id, []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());

        $response = $this->call('patch', 'api/v1/tasks/'.$testTask->id, [
            "billable" => "asldkfj",
        ]);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
    }

    public function test_delete_a_task_successfully()
    {
        $task = $this->generateTestTask();
        $task->save();
        $this->seeInDatabase("tasks", ["name"=>$task->name]);

        $response = $this->call('delete', 'api/v1/tasks/'.$task->id, []);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->notSeeInDatabase("tasks", ["name"=>$task->name]);
    }

    public function test_fail_to_delete_a_task()
    {
        $response = $this->call('delete', 'api/v1/tasks/-1', []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());
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

    /**
     * @return Task
     */
    private function generateTestTask()
    {
        $testTask = new Task();
        $testTask->name = "Test Task";
        $testTask->billable = true;
        return $testTask;
    }
}