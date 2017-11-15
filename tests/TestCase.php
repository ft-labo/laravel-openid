<?php

namespace ForTheLocal\Test;

use ForTheLocal\Laravel\OpenID\ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;


    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);

    }

    private function setUpDatabase($app)
    {

    }

    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            ServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function minifyJson($jsonString)
    {
        return json_encode(json_decode($jsonString));
    }
}