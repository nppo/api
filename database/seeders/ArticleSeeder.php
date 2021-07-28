<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    private const MAX_ARTICLES = 30;

    public function run(): void
    {
        Article::factory()
            ->times(self::MAX_ARTICLES)
            ->create();
    }
}
