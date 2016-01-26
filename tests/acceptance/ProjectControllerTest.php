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

    public function testForCreateFailure()
    {
        $projectName = 'Test Project';

        $response = $this->call('POST', '/projects', []);

        $this->seeJsonContains(["error" => true]);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->assertNotEquals(HTTP::CREATED, $response->status());
        $this->notSeeInDatabase('projects', ['name' => "Test Project"]);

        $response = $this->call('POST', '/projects', ["name" => $projectName]);
        $this->seeJsonContains(["error" => true]);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->assertNotEquals(HTTP::CREATED, $response->status());
        $this->notSeeInDatabase('projects', ['name' => $projectName]);

        $response = $this->call('POST', '/projects', ["status" => true]);
        $this->seeJsonContains(["error" => true]);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->assertNotEquals(HTTP::CREATED, $response->status());
        $this->notSeeInDatabase('projects', ["name" => $projectName, 'status' => true]);
    }

    public function testForCreateSuccess()
    {
        $projectName = 'Test Project';

        $response = $this->call('POST', '/projects', [
            'name'=> $projectName,
            'status' => 1,
        ]);

        $this->seeJsonContains(["name" => $projectName]);
        $this->assertEquals(HTTP::CREATED, $response->status());
        $this->assertNotEquals(HTTP::OK, $response->status());
        $this->seeInDatabase('projects', ['name' => $projectName]);
    }

    public function testForFailedUpdate()
    {
        $updated_project_name = "Changed Project Name";
        $project = $this->createTestProject();
        $project->save();
        $this->seeInDatabase('projects', ['name'=>$project->name]);

        $to_patch = ['name' => $updated_project_name];

        // Call without an ID
        $response = $this->call('patch', '/projects', $to_patch);
        $this->assertEquals(HTTP::METHOD_NOT_ALLOWED, $response->status());

        // call with invalid id
        $response = $this->call('patch', '/projects/-1', $to_patch);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());
    }

    public function testForSuccessfulUpdate()
    {
        $updated_project_name = "Changed Project Name";

        // Create a test project so we can change it, then verify that it's there.
        $project = $this->createTestProject();
        $project->save();
        $this->seeInDatabase('projects', ['name'=>$project->name]);

        // change the project's name, and verify that it's updated
        $response = $this->call('patch', '/projects/'.$project->id, [
            'name' => $updated_project_name,
            ]);

        $this->seeJsonContains([
                 'updated' => true,
                 'name' => $updated_project_name,
             ]);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeInDatabase('projects', ['name'=>$updated_project_name, 'status'=>1]);

        // Change the project's status, and verify that it's updated
        $response = $this->call('patch', '/projects/'.$project->id, [
            'status' => 0,
            ]);
        $this->seeJsonContains([
                'updated' => true,
                'status' => 0,
            ]);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeInDatabase('projects', ['name'=>$updated_project_name, 'status'=>0]);

        // Now change both the status AND the name, and verify that they've been updated
        $response = $this->call('patch', '/projects/'.$project->id, [
                'status' => $project->status,
                'name' => $project->name,
            ]);
        $this->seeJson([
                'updated' => true,
                'status' => $project->status,
                'name' => $project->name,
            ]);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeInDatabase('projects', ['name' => $project->name, "status" => $project->status]);
    }

    public function testForDelete()
    {
        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name' => $project->name]);

        $response = $this->call('delete', '/projects/'.$project->id);

        $this->seeJsonContains([
                 'deleted' => true,
             ]);

        $this->assertEquals(HTTP::OK, $response->status());

        $this->notSeeInDatabase('projects', ["name" => $project->name]);
    }

    public function testForFailedDelete()
    {
        $project = $this->createTestProject();
        $project->save();
        $this->seeInDatabase('projects', ['name' => $project->name]);

        // Call without id
        $response = $this->call('delete', '/projects');
        $this->assertEquals(HTTP::METHOD_NOT_ALLOWED, $response->status());
        $this->seeInDatabase('projects', ["name" => $project->name]);


        // Call with Invalid ID
        $response = $this->call('delete', '/projects/-1');
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());

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
