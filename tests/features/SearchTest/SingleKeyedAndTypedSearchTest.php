<?php

namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;
use Goopil\RestFilter\Tests\Utils\TestModel;

class SingleKeyedAndTypedSearchTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnStringAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('string', $first->string)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['string' => $first->string],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnCharAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('char', $first->char)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['char' => $first->char],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnBoolAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('bool', $first->bool)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['bool' => $first->bool],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnIntAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('int', $first->int)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['int' => $first->int],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDoubleAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('double', $first->double)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['double' => $first->double],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDecimalAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('decimal', $first->decimal)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['decimal' => $first->decimal],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDatetimeAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('datetime', $first->datetime)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['datetime' => $first->toArray()['datetime']],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDateAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('date', $first->date)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['date' => $first->toArray()['date']],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnTimeAttribute()
    {
        $first = TestModel::first();
        $compare = TestModel::where('time', $first->time)->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['time' => $first->time],
        ]);

        $this->assertEquals($compare, $response);
    }
}
