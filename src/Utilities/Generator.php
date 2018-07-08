<?php
namespace Effectix\CodeGen;

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
     * Generate a new random code.
     *
     * @param int $length [description]
     * @throws \Exception
     * @return string [type] [description]
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
            throw new \Exception('Hash length was not set.');
        }
        $pool = $this->pool;
        $hash = '';
        $max = strlen($pool);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $pool[random_int(0, $max - 1)];
        }
        return $hash;
    }
}
