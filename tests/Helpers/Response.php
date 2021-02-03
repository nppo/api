<?php

declare(strict_types=1);

namespace Tests\Helpers;

use GuzzleHttp\Psr7\Response as Psr7Response;

class Response
{
    public static function fakeJson(): Psr7Response
    {
        return new Psr7Response(200, [], '{"data": {"::STRING_KEY::": "::STRING_VALUE::"}}');
    }
}
