<?php
namespace Effectix\CodeGen\Test;

use Effectix\CodeGen\Facades\CodeGen;

class PersistenceTest extends TestCase
{
    /** @test */
    public function can_generate_and_store_a_code()
    {
        $code = CodeGen::setCharacterPool('1234567890abcdef')->setCodeLength(6)->make();

        $this->assertNotEmpty($code);
        $this->assertEquals(strlen($code), 6);
    }

    /** @test */
    public function ()
    {
        
    }
}
