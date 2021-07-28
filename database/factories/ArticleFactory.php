<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Models\Party;
use App\Models\Person;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    private const CONTENT_TEXT = 'nppo.text';

    private const CONTENT_IMAGE = 'nppo.image';

    private const CONTENT_SLIDER = 'nppo.slider';

    private const CONTENT_PROJECT = 'nppo.project';

    private const CONTENT_PRODUCT = 'nppo.product';

    private const CONTENT_PERSON = 'nppo.person';

    private const CONTENT_PARTY = 'nppo.party';

    private const CONTENT_MIN = 4;

    private const CONTENT_MAX = 12;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),

            'preview_url' => 'https://picsum.photos/600/600',
            'summary'     => $this->faker->text,

            'header'  => $this->createContent(self::CONTENT_SLIDER)['images'],
            'content' => $this->seedContent($this->scaffoldContentTypes()),
        ];
    }

    private function scaffoldContentTypes(): array
    {
        $content = [];

        for ($i = 0; $i < rand(self::CONTENT_MIN, self::CONTENT_MAX); $i++) {
            $content[] = Arr::random([
                self::CONTENT_TEXT,
                self::CONTENT_SLIDER,
                self::CONTENT_IMAGE,
                self::CONTENT_PROJECT,
                self::CONTENT_PRODUCT,
                self::CONTENT_PERSON,
                self::CONTENT_PARTY,
            ]);
        }

        return $content;
    }

    private function seedContent(array $content): array
    {
        $newContent = [];

        foreach ($content as $contentType) {
            $newContent[] = $this->createContent($contentType);
        }

        return $newContent;
    }

    private function createContent(string $name): array
    {
        switch ($name) {
            case self::CONTENT_TEXT:
                return [
                    '__component' => $name,
                    'text'        => $this->faker->text,
                ];
            case self::CONTENT_IMAGE:
                return [
                    '__component' => $name,
                    'image'       => [
                        'url' => 'https://picsum.photos/' . rand(150, 900) . '/' . rand(150, 900),
                    ],
                ];
            case self::CONTENT_SLIDER:
                return [
                    '__component' => $name,
                    'images'      => [
                        [
                            'url' => 'https://picsum.photos/' . rand(150, 900) . '/' . rand(150, 900),
                        ],
                        [
                            'url' => 'https://picsum.photos/' . rand(150, 900) . '/' . rand(150, 900),
                        ],
                    ],
                ];
            case self::CONTENT_PROJECT:
                return [
                    '__component' => $name,
                    'identifier'  => Project::factory()->create()->getKey(),
                ];
            case self::CONTENT_PRODUCT:
                return [
                    '__component' => $name,
                    'identifier'  => Product::factory()->create()->getKey(),
                ];
            case self::CONTENT_PERSON:
                return [
                    '__component' => $name,
                    'identifier'  => Person::factory()->create()->getKey(),
                ];
            case self::CONTENT_PARTY:
                return [
                    '__component' => $name,
                    'identifier'  => Party::factory()->create()->getKey(),
                ];
            default:
                throw new InvalidArgumentException('Provided content type does not exist');
        }
    }
}
