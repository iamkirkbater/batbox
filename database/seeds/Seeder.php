<?php

namespace Batbox\Database;

class Seeder extends \Illuminate\Database\Seeder
{
    /**
     * @param int $percent
     * @return bool
     * Returns a boolean based on whether or not the random value is less than the percent passed in.
     * So, if you have a 75% chance of being true, you pass in 75, and if the random value is less than 75 it returns true;
     */
    function getPercentage($percent = 50)
    {
        $rand = mt_rand(1, 100);
        if ($rand <= $percent)
        {
            return true;
        }
        return false;
    }
}