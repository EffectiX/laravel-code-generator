<?php
namespace Effectix\CodeGen\Facades;

use Illuminate\Support\Facades\Facade;

class CodeGen extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CodeGen';
    }
}
