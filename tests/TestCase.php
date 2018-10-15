<?php

namespace Effectix\CodeGen\Test;

use Effectix\CodeGen\Test\Models\User;
use Effectix\CodeGen\Test\Models\Prize;
use Illuminate\Database\Schema\Blueprint;
use Effectix\CodeGen\CodeGeneratorServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
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
        $this->seedModels(Prize::class, User::class);
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);
    }

    protected function createCodesTable()
    {
        include_once '__DIR__'.'/../migrations/create_codes_table.php.stub';

        (new \CreateCodesTable())->up();
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

                if ($table_name === 'prizes') {
                    $table->integer('user_id')->unsigned()->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->text('json')->nullable();
                }
            });
        });
    }
}
