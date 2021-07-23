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

            'likedProducts' => ProductResource::collection($this->resource->likedProducts),

            'likedProjects' => ProjectResource::collection($this->resource->likedProjects),

            'likedPeople' => PersonResource::collection($this->resource->likedPeople),

            'likedParties' => PartyResource::collection($this->resource->likedParties),
        ];
    }
}
