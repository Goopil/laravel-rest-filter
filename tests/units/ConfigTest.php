<?php

namespace Goopil\RestFilter\Tests\Units;

use Goopil\RestFilter\Tests\BaseTestCase;

class ConfigTest extends BaseTestCase
{
    /**
     * @test
     */
    public function configIsRegistered()
    {
        $this->assertEquals(true, \Config::has('queryScope'));
    }
}
