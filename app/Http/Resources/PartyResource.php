<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartyResource extends JsonResource
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
            'name'        => $this->name,
            'description' => $this->description,
        ];
    }
}
