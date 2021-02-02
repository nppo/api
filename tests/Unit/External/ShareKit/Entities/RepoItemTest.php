<?php

declare(strict_types=1);

namespace Tests\Unit\External\ShareKit\Entities;

use App\External\ShareKit\Entities\RepoItem;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RepoItemTest extends TestCase
{
    /** @test */
    public function when_it_has_no_authors_it_will_return_an_empty_collection(): void
    {
        $repoItem = new RepoItem([], []);

        $this->assertInstanceOf(
            Collection::class,
            $repoItem->getAuthors()
        );

        $this->assertEmpty($repoItem->getAuthors());
    }

    /** @test */
    public function when_it_has_authors_it_will_return_a_collection_with_data(): void
    {
        $repoItem = new RepoItem([], ['authors' => [['person' => ['::KEY::' => '::VALUE::']]]]);

        $this->assertInstanceOf(
            Collection::class,
            $repoItem->getAuthors()
        );

        $this->assertNotEmpty($repoItem->getAuthors());
    }
}
