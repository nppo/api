<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit\Filters;

use App\External\ShareKit\Filters\OperatorParser;
use InvalidArgumentException;
use Tests\TestCase;

class OperatorParserTest extends TestCase
{
    /**
     * @dataProvider operatorDataProvider
     *
     * @test
     */
    public function it_can_parse_all_operators(string $symbol, string $operator): void
    {
        $this->assertEquals(
            $operator,
            OperatorParser::parse($symbol)
        );
    }

    /**
     * @dataProvider operatorDataProvider
     *
     * @test
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function it_will_not_parse_the_operator_if_not_needed(string $symbol, string $operator): void
    {
        $this->assertEquals(
            $operator,
            OperatorParser::parse($operator)
        );
    }

    public function it_will_throw_an_error_if_a_symbol_does_not_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        OperatorParser::parse('::STRING::');
    }

    public function operatorDataProvider(): array
    {
        return [
            'Equal'                 => ['=', 'EQ'],
            'Less than'             => ['<', 'LT'],
            'Greater than'          => ['>', 'GT'],
            'Less than or equal'    => ['<=', 'LE'],
            'Greater than or equal' => ['>=', 'GE'],
            'Not equal'             => ['<>', 'NEQ'],
            'Not equal 2'           => ['!=', 'NEQ'],
            'Like'                  => ['LIKE', 'LIKE'],
        ];
    }
}
