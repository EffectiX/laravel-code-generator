<?php
namespace Effectix\CodeGen;

use Effectix\CodeGen\Models\Code;
use Illuminate\Support\ServiceProvider;
use Effectix\CodeGen\Utilities\CodeGenerator;
use PhpCsFixer\InvalidConfigurationException;

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
        ], 'codegen-config-only');

        // publish migration if not already published
        if (! class_exists('CreateCodesTable')) {
            $this->publishes([
                __DIR__.'/../migrations/create_codes_table.php.stub' => database_path("/migrations/".date('Y_m_d_His', time())."_create_codes_table.php"),
            ], 'codegen-migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-code-generator.php',
            'codegen-config'
        );

        $this->app->bind('CodeGen', CodeGenerator::class);
    }

    public static function determineCodesModel(): string
    {
        $code_model = config('laravel-code-generator.custom_model') ?? Code::class;
        if (! is_a($code_model, Code::class, true)) {
            throw InvalidConfigurationException::modelIsNotValid($code_model);
        }
        return $code_model;
    }
}
