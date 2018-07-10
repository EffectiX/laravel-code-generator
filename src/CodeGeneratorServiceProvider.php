<?php
namespace Effectix\CodeGen;

use Illuminate\Support\ServiceProvider;
use Effectix\CodeGen\Utilities\Generator;

/**
 * Code Generator Service Provider class
 */
class CodeGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // publish config
        $this->publishes([
            __DIR__.'/../config/laravel-code-generator.php' => config_path('laravel-code-generator.php'),
        ], 'codegen-config');

        // publish migration if not already published
        if (! class_exists('CreateCodesTable')) {
            $this->publishes([
                __DIR__.'/../migrations/create_codes_table.php.stub' => database_path("/migrations/".date('Y_m_d_His', time())."_create_activity_log_table.php"),
            ], 'codegen-migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-code-generator.php',
            'codegen-config'
        );

        $this->app->bind('CodeGen', Generator::class);
    }
}
