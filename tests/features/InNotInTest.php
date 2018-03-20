<?php

namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;
use Goopil\RestFilter\Tests\Utils\TestModel;

class InNotInTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldReturnOnlyTheRequestedId()
    {
        $ids = [1, 5, 6, 7, 13, 20];
        $compare = TestModel::whereIn('id', $ids)->get()->toArray();
        $response = $this->callEndpoint(['in' => $ids]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldReturnAllButTheRequestedId()
    {
        $ids = [1, 5, 6, 7, 13, 20];
        $compare = TestModel::whereNotIn('id', $ids)->get()->toArray();
        $response = $this->callEndpoint(['notIn' => $ids]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldReturnRequestedIdExceptsTheDiscardedOnes()
    {
        $in = [1, 5, 6, 7, 13, 20];
        $notIn = [1, 5];
        $compare = TestModel::whereNotIn('id', $notIn)->whereIn('id', $in)->get()->toArray();
        $response = $this->callEndpoint(['notIn' => $notIn, 'in' => $in]);

        $this->assertEquals($compare, $response);
    }
}
