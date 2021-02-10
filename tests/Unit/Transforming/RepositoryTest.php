<?php

declare(strict_types=1);

namespace Tests\Unit\Transforming;

use App\Transforming\Repository;
use InvalidArgumentException;
use Tests\Mocks\Transformer;
use Tests\TestCase;

class RepositoryTest extends TestCase
{
    /** @test */
    public function it_will_return_transformers(): void
    {
        $this->assertIsArray($this->getRepository()->all());
    }

    /** @test */
    public function it_can_detect_if_a_transformer_is_registered(): void
    {
        $this->assertFalse($this->getRepository()->exists('TESTTEST'));
    }

    /** @test */
    public function it_will_remember_registered_transformers(): void
    {
        $this->getRepository()->register('TESTTEST', Transformer::class);

        $this->assertTrue($this->getRepository()->exists('TESTTEST'));
    }

    /** @test */
    public function it_can_return_remembered_transformers(): void
    {
        $this->getRepository()->register('TESTTEST', Transformer::class);

        $this->assertEquals(Transformer::class, get_class($this->getRepository()->for('TESTTEST')));
    }

    /** @test */
    public function it_can_flush_transformers(): void
    {
        $this->getRepository()->register('TESTTEST', Transformer::class);

        $this->getRepository()->flush();
        $this->assertEmpty($this->getRepository()->all());
    }

    /** @test */
    public function it_throws_an_exception_when_a_transformer_already_exists(): void
    {
        $this->getRepository()->register('TESTTEST', Transformer::class);

        $this->expectException(InvalidArgumentException::class);

        $this->getRepository()->register('TESTTEST', Transformer::class);
    }

    /** @test */
    public function it_throws_an_exception_when_a_transformer_class_does_not_exist(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getRepository()->register('TESTTEST', 'This\Class\Does\Not\exist');
    }

    /** @test */
    public function it_throws_an_exception_when_no_transformer_exists(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getRepository()->for('DOESNOTEXIST');
    }

    protected function getRepository(): Repository
    {
        return $this->app->make(Repository::class);
    }
}
