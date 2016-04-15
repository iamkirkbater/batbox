<?php

namespace Batbox\Database;

use Batbox\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tasks')->delete();

        Task::create([
            'name' => 'Meeting',
            'billable' => true,
        ]);
        Task::create([
            'name' => 'Web Development',
            'billable' => true,
        ]);
        Task::create([
            'name' => 'Web Design',
            'billable' => true,
        ]);
        Task::create([
            'name' => 'Research',
            'billable' => true,
        ]);
        Task::create([
            'name' => 'Time Not Billable',
            'billable' => true,
        ]);
        Task::create([
            'name' => 'Support',
            'billable' => true,
        ]);

        $this->command->info('Initial Tasks have been seeded.');
    }
}
