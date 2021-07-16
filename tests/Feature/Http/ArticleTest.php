<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /** @test */
    public function show_will_return_not_found_when_a_article_does_not_exist(): void
    {
        $this
            ->getJson(route('api.articles.show', '::DOES_NOT_EXIST::'))
            ->assertNotFound();
    }

    /** @test */
    public function show_will_return_the_article_when_it_exists(): void
    {
        $article = Article::factory()->create();

        $this
            ->getJson(route('api.articles.show', $article))
            ->assertOk()
            ->assertJsonFragment(['id' => $article->getRouteKey()]);
    }
}
