<?php
namespace Effectix\CodeGen;

use Illuminate\Support\ServiceProvider;

/**
 * Code Generator Service Provider class
 */
class CodeGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laravel-code-generator.php' => config_path('laravel-code-generator.php'),
        ], 'codegen-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-code-generator.php', 'codegen-config'
        );
    }
}
