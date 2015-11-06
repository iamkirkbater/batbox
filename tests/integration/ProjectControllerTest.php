<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Batbox\Models\Project;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class ProjectControllerTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    private $factory;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    public function testForProjectsPage()
    {
        $this->visit('/projects')
            ->see('Projects');
    }

    public function testForSingleID()
    {
        $testProject = Project::find(1);
        $this->visit('/projects/1')
             ->seeJson(["name" => $testProject->name]);
    }

    public function testForCreate()
    {
        $projectName = 'Test Project';

        $response = $this->call('POST', '/projects', [
                'name'=> $projectName,
                'status' => 1,
            ]);

        $this->seeJsonContains(["name" => $projectName]);
        $this->assertEquals(HTTP::CREATED, $response->status());

        $this->seeInDatabase('projects', ['name' => $projectName]);
    }

    public function testForUpdate()
    {
        $updated_project_name = "Changed Project Name";

        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name'=>$project->name]);
        $project->name = $updated_project_name;

        $this->patch('/projects/'.$project->id, [
            'name' => "Changed Project Name",
            ])
             ->seeJson([
                 'updated' => true,
                 'name' => $updated_project_name,
             ]);

        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name'=>$project->name, 'status'=>1]);
        $project->status = false;

        $this->patch('/projects/'.$project->id, [
            'status' => 0,
            ])
            ->seeJson([
                'updated' => true,
                'status' => 0,
            ]);

        $updated_project_name = "New Project";
        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name'=>$project->name, 'status'=>1]);
        $project->status = false;
        $project->name = $updated_project_name;

        $this->patch('/projects/'.$project->id, [
                'status' => 0,
                'name' => $updated_project_name,
            ])
            ->seeJson([
                'updated' => true,
                'status' => 0,
                'name' => $updated_project_name,
            ]);
    }

    public function testForDelete()
    {
        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name' => $project->name]);

        $this->delete('/projects/'.$project->id)
             ->seeJson([
                 'deleted' => true,
             ]);

        $this->notSeeInDatabase('projects', ["name" => $project->name]);
    }

    /**
     * @return Project
     */
    private function createTestProject()
    {
        $project = new Project();

        $project->name = "Test Project";
        $project->status = 1;
        return $project;
    }

    private function seedTestDB() {
        \Artisan::call('db:seed');
    }

    private function prepare()
    {
        \Artisan::call('migrate');
        \Mail::pretend(true);
        $this->seedTestDB();

//        $this->factory->define(Project::class, function (Faker\Generator $faker) {
//            return [
//                "name" => $faker->company,
//                "status" => $this->getPercentage(75),
//            ];
//        });
    }

}
