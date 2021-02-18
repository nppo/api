<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Entities;
use App\Http\Resources\EntityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class EntityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EntityResource::collection(
            Arr::flatten(Entities::asArray())
        );
    }
}
