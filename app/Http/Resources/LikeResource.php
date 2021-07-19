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
            'id' => $this->resource->getKey(),

            'liked_products' => $this->resource->likedProducts,

            'liked_projects' => $this->resource->likedProjects,

            'liked_people' => $this->resource->likedPeople,

            'liked_parties' => $this->resource->likedParties,
        ];
    }
}
