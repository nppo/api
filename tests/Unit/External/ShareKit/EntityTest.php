<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit;

use Tests\Mocks\Entity;
use Tests\TestCase;

class EntityTest extends TestCase
{
    /** @test */
    public function it_will_magically_get_properties_from_the_data(): void
    {
        $entity = new Entity(['::KEY::' => '::VALUE::']);

        $this->assertEquals(
            '::VALUE::',
            $entity->{'::KEY::'}
        );
    }
}
