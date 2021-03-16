<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\ProductTypes;
use App\Http\Resources\EntityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class ProductTypeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return EntityResource::collection(ProductTypes::asReferableArray('label'));
    }
}
