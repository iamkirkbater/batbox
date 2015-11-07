<?php


class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://batbox.dev';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function getPercentage($percent = 50)
    {
        $rand = mt_rand(1, 100);
        if ($rand <= $percent)
        {
            return true;
        }
        return false;
    }

    protected function seeError()
    {
        return $this->seeJsonContains(["error" => true]);
    }
}
