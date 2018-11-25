<?php

namespace Goopil\RestFilter\Tests\Features;

use Goopil\RestFilter\Tests\BaseTestCase;

class PaginateTest extends BaseTestCase
{
    /**
     * @test
     */
    public function itShouldReturnAPaginatedPayloadWithDefaultPerPageSetTo15()
    {
        $page = 1;

        $decoded = $this->callEndpoint([
            'page' => $page,
        ]);

        $this->validatePageComposition($decoded, $page);
    }

    /**
     * @test
     */
    public function itShouldReturnAPaginatedPayloadWithDefaultPerPageSetTo15OnPage2()
    {
        $page = 2;

        $decoded = $this->callEndpoint([
            'page' => $page,
        ]);

        $this->validatePageComposition($decoded, $page);
    }

    /**
     * @test
     */
    public function itShouldReturnAPaginatedPayloadWithTheCorrectDataNumberBasedOnPerPageOnPage2()
    {
        $page = 2;
        $perPage = 3;

        $decoded = $this->callEndpoint([
            'page'    => $page,
            'perPage' => $perPage,
        ]);

        $this->validatePageComposition($decoded, $page);

        $this->assertEquals($perPage, $decoded['per_page']);
        $this->assertEquals($perPage, count($decoded['data']));
    }

    protected function validatePageComposition($decoded, $page)
    {
        $this->assertArrayHasKey('total', $decoded);
        $this->assertArrayHasKey('per_page', $decoded);
        $this->assertArrayHasKey('last_page', $decoded);
        $this->assertArrayHasKey('data', $decoded);

        $this->assertArrayHasKey('current_page', $decoded);
        $this->assertEquals($page, $decoded['current_page']);
    }
}
