<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Action;
use App\Http\Requests\LikeStoreRequest;
use App\Http\Resources\LikeResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Way2Web\Force\Http\Controller;

class UserLikeController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $personRepository)
    {
        $this->userRepository = $personRepository;
    }

    public function index($id): LikeResource
    {
        /** @var User $user */
        $user = $this->userRepository->findOrFail($id);

        $this->authorize(Action::VIEW_ANY . 'Like', $user);

        return LikeResource::make($user);
    }

    public function store($userId, LikeStoreRequest $request): LikeResource
    {
        $this->authorize(Action::CREATE . 'Like', $this->userRepository->findOrFail($userId));

        $user = $this->userRepository->addLike(
            $userId,
            Arr::get($request->validated(), 'likable_type'),
            (string) Arr::get($request->validated(), 'likable_id'),
        );

        return LikeResource::make($user);
    }
}
