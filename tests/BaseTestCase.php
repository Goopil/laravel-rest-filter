<?php

namespace Goopil\RestFilter\Tests;

use Illuminate\Foundation\Application;
use Goopil\RestFilter\Scopes\FullScopes;
use Orchestra\Testbench\TestCase as base;
use Goopil\RestFilter\Tests\Utils\TestModel;
use Illuminate\Database\Eloquent\Collection;
use Goopil\RestFilter\Tests\Utils\TestRelatedModel;

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

        $this->loadMigrationsFrom(__DIR__.'/utils/migrations');
        $this->setUpDatabase($this->app);

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
            'Orchestra\Database\ConsoleServiceProvider',
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

    protected function setUpDatabase(Application $app)
    {
        $builder = $app['db']->connection()->getSchemaBuilder();

        if (! $builder->hasTable('test')) {
            $builder->create('test', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('bool');

                $table->char('char');
                $table->string('string');
                $table->text('text');

                $table->integer('int');
                $table->double('double');
                $table->decimal('decimal');

                $table->dateTime('datetime');
                $table->date('date');
                $table->time('time');
                $table->timestamps();
            });
        }

        if (! $builder->hasTable('test_related')) {
            $builder->create('test_related', function (Blueprint $table) {
                $table->increments('id');
                $table->boolean('bool');

                $table->char('char');
                $table->string('string');
                $table->text('text');

                $table->integer('int');
                $table->double('double');
                $table->decimal('decimal');

                $table->dateTime('datetime');
                $table->date('date');
                $table->time('time');
                $table->timestamps();

                $table->unsignedInteger('test_model_id');
            });
        }
    }
}
