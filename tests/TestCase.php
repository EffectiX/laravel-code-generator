<?php

namespace Effectix\CodeGen\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function checkRequirements()
    {
        parent::checkRequirements();

        collect($this->getAnnotations())->filter(function ($location) {
            return in_array('!Travis', array_get($location, 'requires', []));
        })->each(function ($location) {
            getenv('TRAVIS') && $this->markTestSkipped('Travis will not run this test.');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            CodeGeneratorServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('app.key', '123SoSerious4567InsaneInTheBrain');
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();

        $this->createCodesTable();

        $this->createTables('users', 'prizes');
        $this->seedModels(Article::class, User::class);
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);
    }

    protected function createCodesTable()
    {
        include_once '__DIR__'.'/../migrations/create_activity_log_table.php.stub';

        (new \CreateActivityLogTable())->up();
    }

    public function getTempDirectory(): string
    {
        return __DIR__.'/tmp';
    }

    protected function createTables(...$table_names)
    {
        collect($table_names)->each(function (string $table_name) {
            $this->app['db']->connection()->getSchemaBuilder()->create($table_name, function (Blueprint $table) use ($table_name) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->timestamps();
                $table->softDeletes();

                if ($tableName === 'prizes') {
                    $table->integer('user_id')->unsigned()->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->text('json')->nullable();
                }
            });
        });
    }
}
