<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->getKey(),
            'email' => $this->resource['email'],

            'roles' => $this->whenLoaded('roles', function (): AnonymousResourceCollection {
                return RoleResource::collection($this->roles);
            }),

            'permissions' => $this->whenLoaded('permissions', function (): AnonymousResourceCollection {
                return PermissionResource::collection($this->getAllPermissions());
            }),
        ];
    }
}
