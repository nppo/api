<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enumerators\TagTypes;
use App\Models\Theme;
use Database\Factories\Support\TagLikeFactory;

class ThemeFactory extends TagLikeFactory
{
    protected string $tagType = TagTypes::THEME;

    protected $model = Theme::class;
}
