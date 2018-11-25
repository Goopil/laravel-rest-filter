<?php

namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;
use Goopil\RestFilter\Tests\Utils\TestModel;

class IncludeTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldReturnTheModelWithTheRelatedModelDataEagerLoaded()
    {
        $compare = TestModel::with('related')->get()->toArray();
        $response = $this->callEndpoint([
            'include' => 'related',
        ]);

        $this->assertEquals($compare, $response);
    }

    /**
     * @test
     */
    public function ItShouldNotReturnAnyRelatedModelWhenATheRelationDosntExist()
    {
        $compare = TestModel::all()->toArray();
        $response = $this->callEndpoint([
            'include' => 'relatedas',
        ]);

        $this->assertEquals($compare, $response);
    }
}
