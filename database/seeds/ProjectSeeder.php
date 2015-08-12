<?php

namespace Batbox\Database;

use Batbox\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('projects')->delete();

        $faker = \Faker\Factory::create();

        for ($i=0; $i < 100; ++$i)
        {
            Project::create([
                "name" => $faker->company,
                "status" => $this->getPercentage(75),
            ]);
        }
    }
}
