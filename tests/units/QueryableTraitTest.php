<?php

namespace Goopil\RestFilter\Tests\Units;

use Mockery as m;
use Goopil\RestFilter\Tests\BaseTestCase;

class QueryableTraitTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldApplyTheQueryableTraitOnTestQueyableModel()
    {
        $mock = m::mock('Goopil\RestFilter\Tests\Utils\TestQueryableModel')->makePartial();
        $mock->shouldReceive('bootQueryable')->once();
        $mock->__construct();
    }
}
