<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->getKey(),
            'title'       => $this->title,
            'description' => $this->description,
            'views'       => $this->views,

            'likes'       => $this->whenLoaded('views', function (): int {
                return $this->views()->count();
            }),

            'theme'       => $this->whenLoaded('theme', function (): Theme {
                return $this->theme;
            }),

            'tags'        => $this->whenLoaded('tags', function (): Collection {
                return $this->tags;
            })
        ];
    }
}
