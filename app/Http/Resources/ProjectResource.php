<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'likes'       => $this->likes_count,
            'createdAt'   => $this->created_at,

            'parties' => $this->whenLoaded('parties', function (): AnonymousResourceCollection {
                return PartyResource::collection($this->parties);
            }),

            'themes' => $this->whenLoaded('themes', function (): AnonymousResourceCollection {
                return ThemeResource::collection($this->themes);
            }),

            'tags' => $this->whenLoaded('tags', function (): AnonymousResourceCollection {
                return TagResource::collection($this->tags);
            }),

            'products' => $this->whenLoaded('products', function (): AnonymousResourceCollection {
                return ProductResource::collection($this->products);
            }),
        ];
    }
}
