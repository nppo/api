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
            'id'        => $this->getKey(),
            'firstName' => $this->first_name,
            'lastName'  => $this->last_name,
            'email'     => $this->email,
            'function'  => $this->function,
            'phone'     => $this->phone,

            'contributions' => $this->whenLoaded('products', function (): AnonymousResourceCollection {
                return ProductResource::collection($this->products);
            }),

            'tags' => $this->whenLoaded('tags', function (): AnonymousResourceCollection {
                return TagResource::collection($this->tags);
            }),
        ];
    }
}
