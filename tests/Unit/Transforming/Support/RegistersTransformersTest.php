<?php

declare(strict_types=1);

namespace Tests\Unit\Transforming\Support;

use App\Facades\Transformer as FacadesTransformer;
use App\Transforming\Support\RegistersTransformers;
use InvalidArgumentException;
use Tests\Mocks\Transformer;
use Tests\TestCase;

class RegistersTransformersTest extends TestCase
{
    use RegistersTransformers;

    /** @var array|null */
    protected $transformers = [];

    /** @test */
    public function register_transformers_throws_an_error_when_there_are_no_transformers(): void
    {
        $this->transformers = null;

        $this->expectException(InvalidArgumentException::class);

        $this->registerTransformers();
    }

    /** @test */
    public function register_transformers_will_register_transformers_to_the_repository(): void
    {
        $this->transformers = [
            '::STRING::' => Transformer::class,
        ];

        $this->registerTransformers();

        $this->assertTrue(
            FacadesTransformer::exists('::STRING::')
        );
    }
}
