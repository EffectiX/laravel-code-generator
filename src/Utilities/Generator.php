<?php
namespace Effectix\CodeGen\Utilities;

use Effectix\CodeGen\Exceptions\GeneratorInvalidLengthException;
use Effectix\CodeGen\Exceptions\GeneratorInvalidCharacterPoolException;

class Generator
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

    /**
     * Initialize class attributes.
     * @return $this
     */
    public function init()
    {
        $this->pool = config('laravel-code-generator.character_pool', 'abcdef1234567890');
        $this->length = config('laravel-code-generator.length', 5);
        $this->init_run = true;

        return $this;
    }

    /**
     * Set a new code length.
     * @param int $new_length Integer describing the pseudo-random code length.
     */
    public function setCodeLength(int $new_length)
    {
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
            $this->init()->setCharacterPool($new_pool);
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
     * @param $length int The length of the pseudo random code to be generated.
     * @throws GeneratorInvalidLengthException When the $length is not set to a numeric value.
     * @throws GeneratorInvalidCharacterPoolException  When $pool is empty or falsey.
     * @return $code string The generated pseudo-random code.
     */
    public function make($length = null)
    {
        if (! $this->init_run) {
            return $this->init()->make($length);
        }

        if (is_null($length)) {
            $length = $this->length;
        }

        if (! $length || ! is_numeric($length)) {
            throw new GeneratorInvalidLengthException('Hash length was not set.');
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
        return $code;
    }
}
