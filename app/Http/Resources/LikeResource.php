<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Support\WithMetaData;
use Illuminate\Http\Request;
use Way2Web\Force\Http\Resource;

class LikeResource extends Resource
{
    use WithMetaData;

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
            'user_id' => $this->resource->getKey(),

            'liked_products' => ProductResource::collection($this->resource->likedProducts),

            'liked_projects' => ProjectResource::collection($this->resource->likedProjects),

            'liked_people' => PersonResource::collection($this->resource->likedPeople),

            'liked_parties' => PartyResource::collection($this->resource->likedParties),
        ];
    }
}
