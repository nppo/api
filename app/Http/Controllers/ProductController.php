<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function show($id): ProductResource
    {
        return new ProductResource(
            $this->productRepository->findOrFail($id)
        );
    }

    public function update(ProductUpdateRequest $request, $id): ProductResource
    {
        $this->authorize('update', $this->productRepository->findOrFail($id));

        $this
            ->productRepository
            ->update(
                $request->validated(),
                $id
            );

        return new ProductResource(
            $this->productRepository->findOrFail($id)
        );
    }
}
