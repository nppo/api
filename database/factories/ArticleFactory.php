<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    private const CONTENT_TEXT = 'nppo.text';

    private const CONTENT_IMAGE = 'nppo.image';

    private const CONTENT_SLIDER = 'nppo.slider';

    private const CONTENT_MIN = 4;

    private const CONTENT_MAX = 12;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),

            'preview_url' => 'https://picsum.photos/600/600',
            'summary'     => $this->faker->text,

            'content' => $this->seedContent($this->scaffoldContentTypes()),
        ];
    }

    private function scaffoldContentTypes(): array
    {
        $content = [];

        for ($i = 0; $i < rand(self::CONTENT_MIN, self::CONTENT_MAX); $i++) {
            $content[] = Arr::random([self::CONTENT_TEXT, self::CONTENT_SLIDER, self::CONTENT_IMAGE]);
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
                        'url' => 'https://picsum.photos/600/600',
                    ],
                ];
            case self::CONTENT_SLIDER:
                return [
                    '__component' => $name,
                    'images'      => [
                        [
                            'url' => 'https://picsum.photos/600/600',
                        ],
                        [
                            'url' => 'https://picsum.photos/600/600',
                        ],
                    ],
                ];
            default:
                throw new InvalidArgumentException('Provided content type does not exist');
        }
    }
}
