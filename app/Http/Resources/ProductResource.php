<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enumerators\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Resource;

class ProductResource extends Resource
{
    protected array $permissions = [
        Action::UPDATE,
    ];

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
            'likes'       => $this->likes_count,
            'publishedAt' => $this->published_at,

            'parties' => $this->whenLoaded('parties', function (): AnonymousResourceCollection {
                return PartyResource::collection($this->parties);
            }),

            'themes' => $this->whenLoaded('themes', function (): AnonymousResourceCollection {
                return ThemeResource::collection($this->themes);
            }),

            'tags' => $this->whenLoaded('tags', function (): AnonymousResourceCollection {
                return TagResource::collection($this->tags);
            }),
        ];
    }
}
