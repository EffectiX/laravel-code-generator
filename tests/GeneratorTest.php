<?php
namespace Effectix\CodeGen\Test;

use Effectix\CodeGen\Facades\CodeGen;

class GeneratorTest extends TestCase
{
    /** @test */
    public function can_generate_code_with_custom_character_pool_and_length()
    {
        $code = CodeGen::setCharacterPool('1234567890abcdef')->setCodeLength(6)->make();

        $this->assertNotEmpty($code);
        $this->assertEquals(strlen($code), 6);
    }

    /** @test */
    public function can_generate_code_without_manually_calling_init()
    {
        $code = CodeGen::make();

        $this->assertNotEmpty($code);
    }
}
