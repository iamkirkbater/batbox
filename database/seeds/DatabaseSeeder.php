<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(Batbox\Database\ProjectSeeder::class);
        $this->command->info('Project Table Seeded.');

        $this->call(Batbox\Database\TaskSeeder::class);

        Model::reguard();
    }
}
