<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductSearchController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::search($request->get('query'))->get());
    }
}
