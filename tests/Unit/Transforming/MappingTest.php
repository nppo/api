<?php

declare(strict_types=1);

namespace Tests\Unit\Transforming;

use App\Transforming\Map;
use App\Transforming\Mapping;
use stdClass;
use Tests\TestCase;

class MappingTest extends TestCase
{
    /** @test */
    public function it_can_apply_a_mapping_with_array_output(): void
    {
        $mapping = new Mapping([
            new Map('EXAMPLE', '::NEW_KEY::'),
        ]);

        $result = [];

        $mapping->apply(['EXAMPLE' => '::VALUE::'], $result);

        $this->assertArrayHasKey('::NEW_KEY::', $result);
    }

    /** @test */
    public function it_can_apply_a_mapping_with_object_output(): void
    {
        $mapping = new Mapping([
            new Map('EXAMPLE', '::NEW_KEY::'),
        ]);

        $result = new stdClass();

        $mapping->apply(['EXAMPLE' => '::VALUE::'], $result);

        $this->assertEquals(
            '::VALUE::',
            $result->{'::NEW_KEY::'}
        );
    }
}
