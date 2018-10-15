<?php

use Effectix\CodeGen\Utilities\CodeGenerator;


if (! function_exists('codegen')) {
    function codegen(string $code_name = null, int $code_length = null, string $character_pool = null)
    {
        $codegen = app(CodeGenerator::class)->init();
        return $codegen
            ->setCodeName($code_name ?? $default_code_name)
            ->setCodeLength($code_length);
    }
}