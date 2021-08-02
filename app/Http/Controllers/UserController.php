<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function current(Request $request): UserResource
    {
        return UserResource::make(
            $request->user()->load(['person', 'roles', 'permissions'])
        );
    }
}
