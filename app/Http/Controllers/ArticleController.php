<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Repositories\ArticleRepository;
use Way2Web\Force\Http\Controller;

class ArticleController extends Controller
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /** @param mixed $id */
    public function show($id): ArticleResource
    {
        return ArticleResource::make(
            $this->articleRepository->show($id)
        );
    }
}
