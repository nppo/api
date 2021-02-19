<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enumerators\Entities;
use Tests\TestCase;

class EnumTest extends TestCase
{
    /** @test */
    public function it_transforms_an_enum_to_associative_array(): void
    {
        $valueKey = '::value::';

        $referableArray = Entities::asReferableArray($valueKey);

        $this->assertCount(
            count((new \ReflectionClass(Entities::class))->getConstants()),
            $referableArray
        );

        $this->assertArrayHasKey('id', $referableArray[0]);
        $this->assertArrayHasKey($valueKey, $referableArray[0]);
    }

    /** @test */
    public function it_returns_a_specific_value_based_on_referable_key()
    {
        $valueKey = '::value::';

        $referableArray = Entities::asReferableArray($valueKey);

        foreach($referableArray as $entity) {
            $this->assertEquals(
                $entity[$valueKey],
                Entities::getByReferableKey($entity['id'])
            );
        }
    }

    /** @test */
    public function it_returns_a_specific_value_based_on_referable_value()
    {
        $valueKey = '::value::';

        $iWantToSee = 'id';

        $referableArray = Entities::asArray();

        foreach($referableArray as $entity) {
            $this->assertEquals(
                $entity,
                Entities::getByReferableValue($entity)
            );
        }
    }
}
