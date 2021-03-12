<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Resource;

class UserResource extends Resource
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
            'id'    => $this->getKey(),
            'email' => $this->email,

            'roles' => $this->whenLoaded('roles', function (): AnonymousResourceCollection {
                return RoleResource::collection($this->roles);
            }),

            'permissions' => $this->whenLoaded('permissions', function (): AnonymousResourceCollection {
                return PermissionResource::collection($this->getAllPermissions());
            }),
        ];
    }
}
