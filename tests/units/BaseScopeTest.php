<?php

namespace Goopil\RestFilter\Tests\Units;

use Goopil\RestFilter\Scopes\BaseScope;
use Goopil\RestFilter\Tests\BaseTestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseScopeTest extends BaseTestCase
{
    /**
     * @var BaseScope
     */
    private $testedClass;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->testedClass = new class extends BaseScope
        {
            // implement interface
            public function apply(Builder $builder, Model $model)
            {
            }
        };
    }

    /**
     * Gets a protected property on a given object via reflection
     *
     * @param $property - property on instance being modified
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getProtectedProperty($property)
    {
        $reflection = new \ReflectionClass($this->testedClass);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        return $reflection_property->getValue($this->testedClass);
    }

    /**
     * @test
     */
    public function ItShouldHaveInitialParams()
    {
        $this->assertInstanceOf(Request::class, $this->getProtectedProperty('request'));
        $this->assertEquals(config('queryScope.primarySeparator'), $this->getProtectedProperty('primary'));
        $this->assertEquals(config('queryScope.secondarySeparator'), $this->getProtectedProperty('secondary'));
    }

    /**
     * @test
     */
    public function itReturnAnArrayWhenFeededWithAnArray()
    {
        $result = $this->testedClass->hasArray(['test']);

        $this->assertEquals($result, ['test']);
    }

    /**
     * @test
     */
    public function itReturnAnArrayWhenFeededWithString()
    {
        $result = $this->testedClass->hasArray('test');

        $this->assertEquals($result, ['test']);
    }

    /**
     * @test
     */
    public function itReturnAnArrayWhenFeededWithStringIncludingPrimarySeparator()
    {
        $result = $this->testedClass->hasArray('test1;test2');

        $this->assertEquals($result, ['test1', 'test2']);
    }

    /**
     * @test
     */
    public function itReturnAnArrayWhenFeededWithNothing()
    {
        $result = $this->testedClass->hasArray();

        $this->assertEquals($result, []);
    }
}
