<?php

namespace Goopil\RestFilter\Tests;

use Goopil\RestFilter\Scopes\FullScopes;
use Goopil\RestFilter\Tests\Utils\TestModel;
use \Orchestra\Testbench\TestCase as base;

/**
 * base class for test suite
 *
 * Class BaseTestCase
 * @package Goopil\RestFilter\Tests
 */
abstract class BaseTestCase extends base
{
    /**
     * @var string
     */
    private $baseTestRelativeUrl = 'test';

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/utils/migrations');
        $this->withFactories(__DIR__.'/utils/factories');
        $this->artisan('migrate');

        factory(TestModel::class, 20)->create();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /**
         * add in memory db for local testing
         */
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        /**
         * assign local db config if none set by env (ci testing)
         */
        //$app['config']->set('database.default', env('database.default', 'testbench'));

        /**
         * testing url
         */
        $app['router']->get($this->baseTestRelativeUrl, function () {
            // register scope as global
            TestModel::addGlobalScope(new FullScopes);
            return TestModel::all();
        });
    }

    /**
     * register package provider to expose config
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Goopil\RestFilter\RestScopeProvider'
        ];
    }

    /**
     * generic call method with json decode
     *
     * @param array $params
     * @return mixed
     */
    protected function callEndpoint($params = [])
    {
        $response = $this->call('GET', $this->baseTestRelativeUrl, $params);

        return json_decode($response->getContent());
    }
}
