<?php

use Batbox\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends \TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    public function testFormatHumanReadableDate()
    {
        $project = Project::find(1);
        $updated = $project->updated_at;

        $this->assertEquals(date('l, d-M-y H:i:s', strtotime($updated)), $project->last_updated, "Last Updated Time does not equal human readable format.");
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
}
