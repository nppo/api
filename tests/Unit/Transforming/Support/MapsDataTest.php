<?php

declare(strict_types=1);

namespace Tests\Unit\Transforming\Support;

use App\Transforming\Map;
use App\Transforming\Support\MapsData;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class MapsDataTest extends TestCase
{
    use MapsData;

    /** @test */
    public function map_throws_an_error_when_the_passed_output_is_not_an_array_or_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var MockObject|Map */
        $map = $this->createMock(Map::class);

        $output = '::STRING::';

        $this->map($map, [], $output);
    }

    /** @test */
    public function retrieve_input_value_will_call_the_origin_on_the_map(): void
    {
        /** @var MockObject|Map */
        $map = $this->createMock(Map::class);

        $map
            ->expects($this->once())
            ->method('getOrigin')
            ->willReturn('\'::KEY::\'');

        $this->retrieveInputValue($map, []);
    }

    /** @test */
    public function retrieve_input_value_will_return_null_if_it_does_not_find_a_value(): void
    {
        /** @var MockObject|Map */
        $map = $this->createMock(Map::class);

        $map
            ->expects($this->once())
            ->method('getOrigin')
            ->willReturn('\'::KEY::\'');

        $this->assertNull($this->retrieveInputValue($map, []));
    }

    /** @test */
    public function retrieve_input_value_will_return_the_value_if_it_can_find_one(): void
    {
        /** @var MockObject|Map */
        $map = $this->createMock(Map::class);

        $map
            ->expects($this->once())
            ->method('getOrigin')
            ->willReturn('\'::KEY::\'');

        $this->assertEquals(
            '::STRING::',
            $this->retrieveInputValue($map, ['::KEY::' => '::STRING::'])
        );
    }

    /** @test */
    public function retrieve_input_value_will_check_if_the_map_has_a_transformer_type(): void
    {
        /** @var MockObject|Map */
        $map = $this->createMock(Map::class);

        $map
            ->expects($this->once())
            ->method('getOrigin')
            ->willReturn('\'::KEY::\'');

        $map
            ->expects($this->once())
            ->method('getTransformerType')
            ->willReturn(null);

        $this->retrieveInputValue($map, ['::KEY::' => '::STRING::']);
    }
}
