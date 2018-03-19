<?php
namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;

class OffsetLimitTest extends BaseTestCase {

    /**
     * @test
     */
    public function itShouldLimitTheEntitiesToTwo ()
    {
        $decoded = $this->callEndpoint(['limit' => 2]);
        $this->assertCount(2, $decoded);
    }

    /**
     * @test
     */
    public function itShouldApplyTheDefaultLimitIfParamsIsNotValidInt ()
    {
        $decoded = $this->callEndpoint(['limit' => '125']);
        $this->assertCount(15, $decoded);
    }

    /**
     * @test
     */
    public function itShouldApplyOffsetAndLimitAndReturnTheSecondEntities ()
    {
        $decoded = $this->callEndpoint(['offset' => 1, 'limit' => 1]);

        $this->assertCount(1, $decoded);
    }

    /**
     * @test
     */
    public function itShouldApplyOffsetAndReturnAllStartingToSecond () {
        $decoded = $this->callEndpoint(['offset' => 1]);

        $this->assertNotEquals(1, $decoded[0]['id']);
    }

}
