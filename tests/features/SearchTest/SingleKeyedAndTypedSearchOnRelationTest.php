<?php

namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;
use Goopil\RestFilter\Tests\Utils\TestModel;
use Goopil\RestFilter\Tests\Utils\TestRelatedModel;

class SingleKeyedAndTypedSearchOnRelationTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnStringAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('string', $first->string);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.string' => $first->string],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnCharAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('char', $first->char);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.char' => $first->char],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnBoolAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('bool', $first->bool);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.bool' => $first->bool],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnIntAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('int', $first->int);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.int' => $first->int],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDoubleAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('double', $first->double);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.double' => $first->double],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDecimalAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('decimal', $first->decimal);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.decimal' => $first->decimal],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDatetimeAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('datetime', $first->datetime);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.datetime' => $first->datetime],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnDateAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('date', $first->date);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.date' => $first->date],
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldProperlyApplyTheWhereClauseOnTimeAttribute()
    {
        $first = TestRelatedModel::first();
        $compare = TestModel::whereHas('related', function ($query) use ($first) {
            return $query->where('time', $first->time);
        })->get()->toArray();

        $response = $this->callEndpoint([
            'search' => ['related.time' => $first->time],
        ]);

        $this->assertEquals($compare, $response);
    }
}
