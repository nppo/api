<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enumerators\TagTypes;
use App\Models\Keyword;
use Database\Factories\Support\TagLikeFactory;

class KeywordFactory extends TagLikeFactory
{
    protected string $tagType = TagTypes::KEYWORD;

    protected $model = Keyword::class;
}
