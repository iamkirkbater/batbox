<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testForHomepage()
    {
        $this->visit('/')
             ->see('Dashboard');
    }


}
