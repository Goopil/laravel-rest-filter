<?php

namespace Goopil\RestFilter\Tests\Features;

use \Goopil\RestFilter\Tests\BaseTestCase;
use \Goopil\RestFilter\Tests\Utils\TestModel;

class FilterTest extends BaseTestCase
{
    /**
     * @test
     */
    public function ItShouldReturnOnlyTheFilteredField()
    {
        $models = TestModel::all('string');
        $decoded = $this->callEndpoint(['filter' => 'string']);

        $this->assertEquals($models->toArray(), $decoded);
    }

    /**
     * @test
     */
    public function ItShouldReturnOnlyTheMultipleFilteredFieldsWithParamAsArray()
    {
        $models = TestModel::all(['string', 'text']);
        $decoded = $this->callEndpoint(['filter' => ['string', 'text']]);

        $this->assertEquals($models->toArray(), $decoded);
    }

    /**
     * @test
     */
    public function ItShouldReturnOnlyTheMultipleFilteredFieldsWithParamAsStringSeparatedWithPrimary()
    {
        $models = TestModel::all(['string', 'text']);
        $decoded = $this->callEndpoint(['filter' => 'string;text']);

        $this->assertEquals($models->toArray(), $decoded);
    }

    /**
     * @test
     */
    public function ItShouldReturnUnfilteredFieldsIfParamIsEmptyString()
    {
        $models = TestModel::all();
        $decoded = $this->callEndpoint(['filter' => '']);

        $this->assertEquals($models->toArray(), $decoded);
    }

    /**
     * @test
     */
    public function ItShouldReturnUnfilteredFieldsIfParamIsNull()
    {
        $models = TestModel::all();
        $decoded = $this->callEndpoint(['filter' => null]);

        $this->assertEquals($models->toArray(), $decoded);
    }
}
