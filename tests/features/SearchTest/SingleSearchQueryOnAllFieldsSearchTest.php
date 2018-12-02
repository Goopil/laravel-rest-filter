<?php
//
//namespace Goopil\RestFilter\Tests\Features;
//
//use Goopil\RestFilter\Tests\BaseTestCase;
//use Goopil\RestFilter\Tests\Utils\TestModel;
//
//class SingleSearchQueryOnAllFieldsSearchTest extends BaseTestCase
//{
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnStringAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('string', $first->string)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->string,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnCharAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('char', $first->char)->orWhereHas('related', function ($query) use ($first) {
//            return $query->where('char', $first->char);
//        })->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->char,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnBoolAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('bool', $first->bool)->orWhereHas('related', function ($query) use ($first) {
//            return $query->where('bool', $first->bool);
//        })->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->bool,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnIntAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('int', $first->int)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->int,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnDoubleAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('double', $first->double)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->double,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnDecimalAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('decimal', $first->decimal)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->decimal,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnDatetimeAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('datetime', $first->datetime)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->toArray()['datetime'],
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnDateAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('date', $first->date)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->toArray()['date'],
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//
//    /**
//     * @test
//     */
//    public function ItShouldProperlyApplyTheWhereClauseOnTimeAttribute()
//    {
//        $first = TestModel::first();
//        $compare = TestModel::where('time', $first->time)->get()->toArray();
//
//        $response = $this->callEndpoint([
//            'search' => $first->time,
//        ]);
//
//        $this->assertEquals($compare, $response);
//    }
//}
