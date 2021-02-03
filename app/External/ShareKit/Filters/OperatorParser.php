<?php

declare(strict_types=1);

namespace App\External\ShareKit\Filters;

use InvalidArgumentException;
use Way2Web\Force\Enum;

class OperatorParser extends Enum
{
    protected static array $operators = [
        '='    => 'EQ',
        '<'    => 'LT',
        '>'    => 'GT',
        '<='   => 'LE',
        '>='   => 'GE',
        '<>'   => 'NEQ',
        '!='   => 'NEQ',
        'LIKE' => 'LIKE',
    ];

    public static function parse(string $operator): string
    {
        $operators = self::$operators;

        if (array_key_exists($operator, $operators)) {
            return $operators[$operator];
        }

        if (in_array($operator, $operators)) {
            return $operator;
        }

        throw new InvalidArgumentException('Invalid operator provided');
    }
}
