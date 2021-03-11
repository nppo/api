<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'id'                  => $this->getKey(),
            'identifier'          => $this->identifier,
            'firstName'           => $this->first_name,
            'lastName'            => $this->last_name,
            'about'               => $this->about,
            'email'               => $this->email,
            'function'            => $this->function,
            'phone'               => $this->phone,
            'profile_picture_url' => $this->profile_picture_url,

            'parties' => $this->whenLoaded('parties', function (): AnonymousResourceCollection {
                return PartyResource::collection($this->parties);
            }),

            'products' => $this->whenLoaded('products', function (): AnonymousResourceCollection {
                return ProductResource::collection($this->products);
            }),

            'projects' => $this->whenLoaded('projects', function (): AnonymousResourceCollection {
                return ProjectResource::collection($this->projects);
            }),

            'tags' => $this->whenLoaded('tags', function (): AnonymousResourceCollection {
                return TagResource::collection($this->tags);
            }),

            'themes' => $this->whenLoaded('themes', function (): AnonymousResourceCollection {
                return ThemeResource::collection($this->themes);
            }),
        ];
    }
}
