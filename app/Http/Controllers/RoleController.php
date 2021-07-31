<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Repositories\RoleRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class RoleController extends Controller
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(
            $this->roleRepository->index()
        );
    }
}
