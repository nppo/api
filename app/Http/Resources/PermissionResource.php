<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Way2Web\Force\Http\Resource;

class PermissionResource extends Resource
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
            'id'   => $this->getKey(),
            'name' => $this->name,
        ];
    }
}
