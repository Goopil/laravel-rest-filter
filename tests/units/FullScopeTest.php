<?php

namespace Goopil\RestFilter\Tests\Units;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Goopil\RestFilter\Scopes\FullScopes;
use Goopil\RestFilter\Scopes\SearchScope;
use Goopil\RestFilter\Tests\BaseTestCase;
use Illuminate\Database\Eloquent\Builder;

class FullScopeTest extends BaseTestCase
{
    protected $testedClass;

    /**
     * Gets a protected property on a given object via reflection.
     *
     * @param $property - property on instance being modified
     *
     * @throws \ReflectionException
     *
     * @return mixed
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
    public function ItShouldSetTheScopesWithAClassNameSpaceWithAnArrayAsArgument()
    {
        $this->testedClass = new FullScopes();
        $scope = SearchScope::class;

        $this->testedClass->setScopes([$scope]);

        $this->assertEquals([new $scope], $this->getProtectedProperty('scopes'));
    }

    /**
     * @test
     */
    public function ItShouldSetTheScopesWithAClassInstanceSpaceWithAnArrayAsArgument()
    {
        $this->testedClass = new FullScopes();
        $singleScopeAsArray = [
            new SearchScope,
        ];

        $this->testedClass->setScopes($singleScopeAsArray);

        $this->assertEquals($singleScopeAsArray, $this->getProtectedProperty('scopes'));
    }

    /**
     * @test
     */
    public function ItShouldSetTheScopesWithAClassInstanceSpaceAsASingleArgument()
    {
        $this->testedClass = new FullScopes();
        $singleScopeAsString = SearchScope::class;

        $this->testedClass->setScopes($singleScopeAsString);

        $this->assertEquals([new $singleScopeAsString()], $this->getProtectedProperty('scopes'));
    }


    /**
     * @test
     */
    public function itShouldSetTheScopeToBeAppliedOnNextRequestFeedingItAString()
    {
        $this->testedClass = new FullScopes();
        $singleScopeAsString = new SearchScope;

        $this->testedClass->setScopes($singleScopeAsString);

        $this->assertEquals([$singleScopeAsString], $this->getProtectedProperty('scopes'));
    }

    /**
     * @test
     */
    public function ItShouldKeepOnlyOneInstanceOfTheSameScope()
    {
        $this->testedClass = new FullScopes();
        $firstScopeAsString = new SearchScope;
        $secondScopeAsString = new SearchScope;

        $this->testedClass->setScopes([$firstScopeAsString, $secondScopeAsString]);

        $this->assertEquals([$firstScopeAsString], $this->getProtectedProperty('scopes'));
    }

    /**
     * @test
     */
    public function ItShouldTrowAnInvalidArgumentExceptionIfWhatYouInjectIsNotAScope()
    {
        $this->testedClass = new FullScopes();
        $scope = new \stdClass();
        $this->expectException(\InvalidArgumentException::class);

        $this->testedClass->setScopes($scope);
    }

    /**
     * @test
     */
    public function ItShouldAddANewScopeToTheheap()
    {
        $this->testedClass = new FullScopes();
        $scope = new class implements Scope {
            public function apply(Builder $builder, Model $model)
            {
            }
        };

        $defaultScopes = $this->getProtectedProperty('scopes');
        array_push($defaultScopes, $scope);

        $this->testedClass->addScopes($scope);

        $this->assertEquals($defaultScopes, $this->getProtectedProperty('scopes'));
    }
}
