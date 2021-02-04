<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductSearchController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(ProductSearchRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection($this->productRepository->search($request->get('query') ?: ''));
    }
}
