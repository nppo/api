<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Way2Web\Force\Repository\AbstractRepository;

class ArticleRepository extends AbstractRepository
{
    public function model(): string
    {
        return Article::class;
    }

    public function show($id): Model
    {
        return $this
            ->findOrFail($id);
    }
}
