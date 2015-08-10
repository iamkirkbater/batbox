<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Batbox\Models\Project;

class ProjectControllerTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    public function testForProjectsPage()
    {
        $this->visit('/project')
            ->see('Projects');
    }

    public function testForSingleID()
    {
        $this->visit('/project/10')
             ->see('10');
    }

    public function testForCreate()
    {
        $projectName = 'Test Project';

        $this->post('/project', [
                'name'=> $projectName,
                'status' => 1,
            ])
             ->seeJson([
                 'created' => true,
             ]);

        $this->seeInDatabase('projects', ['name' => $projectName]);
    }

    public function testForUpdate()
    {
        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name'=>$project->name]);

        $this->patch('/project/'.$project->id, [
             'name' => "Changed Project Name",
             ])
             ->seeJson([
                 'updated' => true,
                 'name' => "Changed Project Name",
             ]);
    }

    public function testForDelete()
    {
        $project = $this->createTestProject();
        $project->save();

        $this->seeInDatabase('projects', ['name' => $project->name]);

        $this->delete('/project/'.$project->id)
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

}
