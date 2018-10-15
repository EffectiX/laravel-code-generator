<?php
namespace Effectix\CodeGen\Test\Models;

use Effectix\CodeGen\Models\Code;

class CustomCodeModel extends Code
{
    public function getCustomProperty(string $property)
    {
        return $this->getExtraProperty($property);
    }
}
