<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->protectActionRoutes(['api']);
        $this->productRepository = $productRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $this->productRepository->index()
        );
    }

    public function show($id): ProductResource
    {
        return ProductResource::make(
            $this->productRepository->show($id)
        )
            ->withPermissions();
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

        return ProductResource::make(
            $this->productRepository->findOrFail($id)
        )
            ->withPermissions();
    }
}
