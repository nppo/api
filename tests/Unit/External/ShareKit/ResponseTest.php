<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit;

use App\External\ShareKit\Response;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    /** @test */
    public function it_can_get_the_data_from_the_response(): void
    {
        $response = new Response(200, '{"data": {"::STRING_KEY::": "::STRING_VALUE::"}}');

        $this->assertEquals(
            ['::STRING_KEY::' => '::STRING_VALUE::'],
            $response->getData()
        );
    }
}
