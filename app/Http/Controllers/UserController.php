<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth:api')->only(['current']);

        $this->userRepository = $userRepository;
    }

    public function current(Request $request): UserResource
    {
        return UserResource::make(
            $request->user()->load(['roles', 'permissions'])
        );
    }
}
