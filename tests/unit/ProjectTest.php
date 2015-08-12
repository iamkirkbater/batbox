<?php
/**
 * Created by PhpStorm.
 * User: kirkbater
 * Date: 8/12/15
 * Time: 11:40 AM
 */

namespace Batbox\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends \TestCase {

    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    private function prepare()
    {
        \Artisan::call('migrate');
        \Mail::pretend(true);
    }
}
