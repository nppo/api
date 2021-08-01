<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enumerators\TagTypes;
use App\Models\Skill;
use Database\Factories\Support\TagLikeFactory;

class SkillFactory extends TagLikeFactory
{
    protected string $tagType = TagTypes::SKILL;

    protected $model = Skill::class;
}
