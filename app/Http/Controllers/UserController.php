<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->userRepository->index()
        );
    }

    public function show(string $id): UserResource
    {
        return UserResource::make($this->userRepository->show($id));
    }

    public function store(StoreRequest $storeRequest): UserResource
    {
        $this->authorize('create', User::class);

        $user = $this->userRepository->create($storeRequest->data());

        $user = $this->userRepository->syncRoles($user->getKey(), $storeRequest->roles());

        return UserResource::make($user);
    }

    public function update(string $id, UpdateRequest $updateRequest): UserResource
    {
        $user = $this->userRepository->findOrFail($id);

        $this->authorize('update', $user);

        $user = $this->userRepository->updateFull($id, $updateRequest->data());
        $user = $this->userRepository->syncRoles($id, $updateRequest->roles());

        return UserResource::make($user);
    }

    public function destroy(string $id): UserResource
    {
        $user = $this->userRepository->findOrFail($id);

        $this->authorize('delete', $user);

        $user = $this->userRepository->deleteFull($id);

        return UserResource::make($user);
    }

    public function current(Request $request): UserResource
    {
        return UserResource::make(
            $request->user()->load(['person', 'roles', 'permissions'])
        );
    }
}
