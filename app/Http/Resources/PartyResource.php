<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Resource;

class PartyResource extends Resource
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
            'id'              => $this->getKey(),
            'name'            => $this->name,
            'description'     => $this->description,
            'partyPictureUrl' => $this->party_picture_url,

            'parties' => $this->whenLoaded('parties', function (): AnonymousResourceCollection {
                return self::collection($this->parties);
            }),

            'products' => $this->whenLoaded('products', function (): AnonymousResourceCollection {
                return ProductResource::collection($this->products);
            }),

            'projects' => $this->whenLoaded('projects', function (): AnonymousResourceCollection {
                return ProjectResource::collection($this->projects);
            }),
        ];
    }
}
