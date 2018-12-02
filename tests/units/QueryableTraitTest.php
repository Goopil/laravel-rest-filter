<?php

namespace Goopil\RestFilter\Tests\Units;

use Goopil\RestFilter\Tests\BaseTestCase;
use Mockery as m;

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
