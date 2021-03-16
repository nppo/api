<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\MediaCollections;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Way2Web\Force\Http\Controller;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->protectActionRoutes(['api']);
        $this->productRepository = $productRepository;
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

    public function store(ProductStoreRequest $request): ProductResource
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();

        /** @var Product */
        $product = $this
            ->productRepository
            ->create(Arr::except($validated, ['file', 'tags', 'themes']));

        if ($request->hasFile('file')) {
            $product
                ->addMediaFromRequest('file')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
        }

        if (isset($validated['tags'])) {
            $product->tags()->sync(
                Collection::make($validated['tags'])->map(fn ($tag) => $tag['id'])
            );
        }

        if (isset($validated['themes'])) {
            $product->themes()->sync(
                Collection::make($validated['themes'])->map(fn ($theme) => $theme['id'])
            );
        }

        if (isset($validated['parties'])) {
            $product->parties()->syncWithPivotValues(
                Collection::make($validated['parties'])
                    ->map(fn ($party) => $party['id']),
                [
                    'is_owner' => false,
                ]
            );
        }

        if (isset($validated['people'])) {
            $product->people()->syncWithPivotValues(
                Collection::make($validated['people'])
                    ->map(fn ($person) => $person['id']),
                [
                    'is_owner' => false,
                ]
            );
        }

        $product->people()->attach($request->user()->person, ['is_owner' => true]);

        return ProductResource::make(
            $this->productRepository->show($product->getKey())
        );
    }
}
