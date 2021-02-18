<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Entities;
use App\Http\Resources\EntityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EntityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EntityResource::collection(
            Entities::asReferableArray('label')
        );
    }
}
