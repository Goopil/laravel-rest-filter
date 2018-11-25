<?php

namespace Goopil\RestFilter\Tests\Features;

use \Goopil\RestFilter\Tests\BaseTestCase;
use \Goopil\RestFilter\Tests\Utils\TestModel;

class OrderTest extends BaseTestCase
{
    /**
     * @test
     */
    public function itShouldReturnTheDataOrderedByStringWithNoSortedByParamAndUseSortInAscAsDefault()
    {
        $orderBy = 'string';

        $compare  = TestModel::orderBy($orderBy)->get()->toArray();
        $response = $this->callEndpoint([
            'orderBy' => $orderBy,
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function itShouldReturnTheDataOrderedByStringWithSpecificSortInAscLowerCased()
    {
        $orderBy  = 'string';
        $sortedBy = 'asc';

        $compare  = TestModel::orderBy($orderBy, $sortedBy)->get()->toArray();
        $response = $this->callEndpoint([
            'orderBy'  => $orderBy,
            'sortedBy' => $sortedBy
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function itShouldReturnTheDataOrderedByStringWithSpecificSortInAscUpperCased()
    {
        $orderBy  = 'string';
        $sortedBy = 'ASC';

        $compare  = TestModel::orderBy($orderBy, $sortedBy)->get()->toArray();
        $response = $this->callEndpoint([
            'orderBy'  => $orderBy,
            'sortedBy' => $sortedBy
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function itShouldReturnTheDataOrderedByStringWithSortInDESCInUpperCased()
    {
        $orderBy  = 'string';
        $sortedBy = 'DESC';

        $compare  = TestModel::orderBy($orderBy, $sortedBy)->get()->toArray();
        $response = $this->callEndpoint([
            'orderBy'  => $orderBy,
            'sortedBy' => $sortedBy
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function itShouldReturnTheDataOrderedByStringWithSortInDescLowerCased()
    {
        $orderBy  = 'string';
        $sortedBy = 'desc';

        $compare  = TestModel::orderBy($orderBy, $sortedBy)->get()->toArray();
        $response = $this->callEndpoint([
            'orderBy'  => $orderBy,
            'sortedBy' => $sortedBy
        ]);

        $this->assertEquals($compare, $response);
    }
}
