<?php
namespace Effectix\CodeGen\Utilities;

use Illuminate\Database\Eloquent\Model;
use Effectix\CodeGen\Models\Code;
use Effectix\CodeGen\Exceptions\GeneratorPoolLengthException;
use Effectix\CodeGen\Exceptions\GeneratorInvalidLengthException;
use Effectix\CodeGen\Exceptions\GeneratorInvalidCharacterPoolException;

class CodeGenerator
{
    /**
    * Characters to use when generating a pseudo-random string.
    * @var string
    */
    protected $pool = null;

    /**
     * Length of the pseudo-random string to be generated.
     * @var null
     */
    protected $length = null;

    /**
     * Whether or not init has run.
     * @var boolean
     */
    protected $init_run = false;

    /** @var Illuminate\Support\Collection A collection of code properties */
    protected $properties;

    /** @var string Name a purpose for this code. Can configure default codename in package's published config file. */
    protected $code_name;

    /**
     * Model representing the target owner of this code.
     * @var mixed  null|Illuminate\Database\Eloquent\Model
     */
    protected $generated_for = null;

    /**
     * Model representing the creator of the code.
     * @var mixed  null|Illuminate\Database\Eloquent\Model
     */
    protected $generated_by = null;

    /**
     * Initialize class attributes.
     * @return $this
     */
    public function init()
    {
        $this->pool = config('laravel-code-generator.character_pool', 'abcdef1234567890');
        $this->length = config('laravel-code-generator.length', 5);
        $this->model_class = config('laravel-code-generator.custom_model', Code::class);
        $this->code_name = config('laravel-code-generator.default_code_name');

        $this->init_run = true;

        return $this;
    }

    public function setCodeName(string $new_name = null)
    {
        if ($this->init_run == false) {
            return $this->init()->setCodeName($new_name);
        }
        
        if ($new_name) {
            $this->code_name = $new_name;
        }

        return $this;
    }

    /**
     * Set a new code length.
     * @param int $new_length Integer describing the pseudo-random code length.
     */
    public function setCodeLength(int $new_length = null)
    {
        if ($new_length && $this->init_run == false) {
            return $this->init()->setCodeLength($new_length);
        }

        if (!$new_length) {
            return $this;
        }

        $this->length = $new_length;
        
        return $this;
    }

    /**
     * Change the character pool for pseudo-random code generation.
     * @param string $string [description]
     */
    public function setCharacterPool(string $new_pool)
    {
        if ($this->init_run == false) {
            return $this->init()->setCharacterPool($new_pool);
        }

        if (! empty($new_pool)) {
            $this->pool = $new_pool;
            return $this;
        } else {
            throw new GeneratorPoolLengthException('Attempted to define a new character pool with an empty string.');
        }
    }

    /**
     * Generate a new pseudo-random code.
     *
     * @param int $length The length of the pseudo random code to be generated.
     * @param Model obj $codeBy Model class that generates the code.
     * @param Model obj $codeFor Model class that the code is generated for.
     * @throws GeneratorInvalidLengthException When the $length is not set to a numeric value.
     * @throws GeneratorInvalidCharacterPoolException  When $pool is empty or falsey.
     * @return $code string The generated pseudo-random code.
     */
    public function make(Model $generated_for = null, Model $generated_by = null)
    {
        if (! $this->init_run) {
            return $this->init()->make($persist, $codeBy, $codeFor);
        }

        $length = $this->length;

        if ($length === 0 || ! $length || ! is_numeric($length)) {
            throw new GeneratorInvalidLengthException('Hash length was not set or was set to zero length.');
        }

        $pool = $this->pool;

        if (! $pool || empty($pool)) {
            throw new GeneratorInvalidCharacterPoolException('The character pool is empty or not initialized. Please define a character pool in order to generate pseudo-random codes. Did you run "php artisan vendor:publish --tag=codegen-config" ?');
        }
        
        $code = '';
        $max = strlen($pool);
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $pool[random_int(0, ($max - 1))];
        }

        $this->code = $code;
        
        if ($codeFor === true) {
            if ($generated_form !== null) {
                $this->codeMadeBy($generated_form);
            }

            if ($generated_for !== null) {
                $this->codeMadeFor($generated_for);
            }

            $this->storeCode($code);
        }


        return $code;
    }

    protected function codeMadeFor(Model $model)
    {
        $this->code_for = $model;

        return $this;
    }

    protected function codeMadeBy(Model $model)
    {
        $this->code_by = $model;

        return $this;
    }

    protected function storeCode()
    {
        return $codeModel = ($this->model_class)::create([
            'code_name' => $this->code_name,
            'code' => $code,

        ]);
    }
}
