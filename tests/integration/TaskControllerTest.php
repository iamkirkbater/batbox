<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Batbox\Models\Task;

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