<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enumerators\Action;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Way2Web\Force\Http\Resource;

class UserResource extends Resource
{
    protected array $permissions = [
        Action::UPDATE,
        Action::DELETE,
    ];

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

            'person' => $this->whenLoaded('person', function (): JsonResource {
                return PersonResource::make($this->person);
            }),

            'roles' => $this->whenLoaded('roles', function (): AnonymousResourceCollection {
                return RoleResource::collection($this->roles);
            }),

            'permissions' => $this->whenLoaded('permissions', function (): AnonymousResourceCollection {
                return PermissionResource::collection($this->getAllPermissions());
            }),
        ];
    }
}
