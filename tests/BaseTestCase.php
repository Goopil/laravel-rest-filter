<?php

namespace Goopil\RestFilter\Tests;

use Goopil\RestFilter\Scopes\FullScopes;
use Goopil\RestFilter\Tests\Utils\TestModel;
use Goopil\RestFilter\Tests\Utils\TestRelatedModel;
use Illuminate\Database\Eloquent\Collection;
use Orchestra\Testbench\TestCase as base;

/**
 * base class for test suite.
 *
 * Class BaseTestCase
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
    protected function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/utils/migrations');
        $this->withFactories(__DIR__ . '/utils/factories');
        $this->artisan('migrate:refresh');

        /**
         * @var Collection
         */
        $models = factory(TestModel::class, 20)->create();

        $models->each(function (TestModel $model) {
            $model->related()->createMany(
                factory(TestRelatedModel::class, 5)->make()->toArray()
            );
        });
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /*
         * add in memory db for local testing
         */
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        /*
         * assign local db config if none set by env (ci testing)
         */
        if (env('DB_CONNECTION') === null) {
            $app['config']->set('database.default', env('database.default', 'testbench'));
        }

        /*
         * testing url
         */
        $app['router']->get($this->baseTestRelativeUrl, function () {
            // register scope as global
            TestModel::addGlobalScope(new FullScopes());

            return TestModel::all();
        });
    }

    /**
     * register package provider to expose config.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Goopil\RestFilter\RestScopeProvider',
        ];
    }

    /**
     * generic call method with json decode.
     *
     * @param array $params
     *
     * @return mixed
     */
    protected function callEndpoint($params = [])
    {
        $response = $this->call('GET', $this->baseTestRelativeUrl, $params);

        return json_decode($response->getContent(), true);
    }

    protected function loadMigrationsFrom($paths)
    {
        $paths = (is_array($paths)) ? $paths : [$paths];
        $this->app->afterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array)$paths as $path) {
                $migrator->path($path);
            }
        });
    }
}
