<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enumerators\Action;
use App\Http\Resources\Support\WithMetaData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Way2Web\Force\Http\Resource;

class ProductResource extends Resource
{
    use WithMetaData;

    protected array $permissions = [
        Action::UPDATE,
        Action::DELETE,
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
            'type'        => $this->type,
            'title'       => $this->title,
            'summary'     => $this->summary,
            'description' => $this->description,
            'likes'       => $this->likes_count,
            'publishedAt' => $this->published_at,

            'owner' => $this->whenLoaded('owner', function (): PersonResource {
                return PersonResource::make($this->owner->first());
            }),

            'parties' => $this->whenLoaded('parties', function (): AnonymousResourceCollection {
                return PartyResource::collection($this->parties);
            }),

            'people' => $this->whenLoaded('people', function (): AnonymousResourceCollection {
                return PersonResource::collection($this->people);
            }),

            'projects' => $this->whenLoaded('projects', function (): AnonymousResourceCollection {
                return ProjectResource::collection($this->projects);
            }),

            'themes' => $this->whenLoaded('themes', function (): AnonymousResourceCollection {
                return ThemeResource::collection($this->themes);
            }),

            'keywords' => $this->whenLoaded('keywords', function (): AnonymousResourceCollection {
                return KeywordResource::collection($this->keywords);
            }),

            'children' => $this->whenLoaded('children', function (): AnonymousResourceCollection {
                return self::collection($this->children);
            }),

            'parents' => $this->whenLoaded('parents', function (): JsonResource {
                return self::collection($this->parents);
            }),

            'meta' => $this->whenLoaded('attributes', function (): AnonymousResourceCollection {
                if ($this->whenLoaded('values')) {
                    return AttributeResource::collection($this->aggregateAttributes());
                }

                return AttributeResource::collection($this->attributes);
            }),

            'links' => [
                'preview' => $this->object_url,
            ],
        ];
    }
}
