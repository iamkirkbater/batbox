<?php

namespace Batbox\Tests;

trait PrepareForTests
{
    public function prepare()
    {
        \Artisan::call('migrate');
        \Mail::pretend(true);
    }
}