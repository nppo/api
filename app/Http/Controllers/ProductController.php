<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\MediaCollections;
use App\Enumerators\TagTypes;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
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

    public function show(string $id): ProductResource
    {
        return ProductResource::make(
            $this->productRepository->show($id)
        )
            ->withPermissions();
    }

    public function update(ProductUpdateRequest $request, string $id): ProductResource
    {
        /** @var Product $product */
        $product = $this->productRepository->findOrFail($id);

        $this->authorize('update', $product);

        $validated = $request->validated();

        $product->update($validated);

        if ($request->hasFile('file')) {
            $product
                ->addMediaFromRequest('file')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
        }

        $product->syncTags(
            Collection::make(Arr::get($validated, 'keywords') ?? [])
                ->map(fn ($keyword) => $keyword['label'])
                ->toArray(),
            TagTypes::KEYWORD
        );

        $product->syncTags(
            Collection::make(Arr::get($validated, 'themes') ?? [])
                ->map(fn ($theme) => $theme['label'])
                ->toArray(),
            TagTypes::THEME
        );

        $this
            ->syncRelation($product, 'children', Arr::get($validated, 'children') ?? [])
            ->syncRelation($product, 'people', Arr::get($validated, 'people') ?? [], ['is_owner' => false])
            ->syncRelation($product, 'parties', Arr::get($validated, 'parties') ?? [], ['is_owner' => false]);

        $product->people()->attach($request->user()->person, ['is_owner' => true]);

        return ProductResource::make(
            $this->productRepository->show($id)
        )
            ->withPermissions();
    }

    public function store(ProductStoreRequest $request): ProductResource
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();

        /** @var Product $product */
        $product = $this
            ->productRepository
            ->create($validated);

        if ($request->hasFile('file')) {
            $product
                ->addMediaFromRequest('file')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
        }

        $product->syncTags(
            Collection::make(Arr::get($validated, 'keywords') ?? [])
                ->map(fn ($keyword) => $keyword['label'])
                ->toArray(),
            TagTypes::KEYWORD
        );

        $product->syncTags(
            Collection::make(Arr::get($validated, 'themes') ?? [])
                ->map(fn ($theme) => $theme['label'])
                ->toArray(),
            TagTypes::THEME
        );

        $this
            ->syncRelation($product, 'children', Arr::get($validated, 'children') ?? [])
            ->syncRelation($product, 'people', Arr::get($validated, 'people') ?? [], ['is_owner' => false])
            ->syncRelation($product, 'parties', Arr::get($validated, 'parties') ?? [], ['is_owner' => false]);

        $product->people()->attach($request->user()->person, ['is_owner' => true]);

        return ProductResource::make(
            $this->productRepository->show($product->getKey())
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        $this->authorize('delete', $product);

        $this->productRepository->softDelete($id);

        return Response::json([], 204);
    }

    /** @param mixed $id */
    public function download($id): Responsable
    {
        /** @var Product */
        $product = $this->productRepository->findOrFail($id);

        return $product->getFirstMedia(MediaCollections::PRODUCT_OBJECT);
    }
}
