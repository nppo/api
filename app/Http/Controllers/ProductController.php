<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\MediaCollections;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        /** @var Product $product */
        $product = $this->productRepository->findOrFail($id);

        $this->authorize('update', $product);

        $validated = $request->validated();

        $this
            ->productRepository
            ->update(
                Arr::except(
                    $request->validated(),
                    ['file', 'tags', 'themes', 'people', 'parties', 'children', 'parent']
                ),
                $id
            );

        if ($request->hasFile('file')) {
            $product
                ->addMediaFromRequest('file')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PRODUCT_OBJECT);
        }

        $product->syncTags(
            Collection::make(Arr::get($validated, 'tags') ?? [])
                ->map(fn ($tag) => $tag['label'])
                ->toArray()
        );

        $this->syncHasManyRelation($product, $this->productRepository, 'children', $validated);
        $this->syncBelongsToRelation($product, $this->productRepository, 'parent', $validated);

        $this
            ->syncRelation($product, 'themes', Arr::get($validated, 'themes') ?? [])
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

        /** @var Product */
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
            Collection::make(Arr::get($validated, 'tags') ?? [])
                ->map(fn ($tag) => $tag['label'])
                ->toArray()
        );

        $this
            ->syncRelation($product, 'themes', Arr::get($validated, 'themes') ?? [])
            ->syncRelation($product, 'people', Arr::get($validated, 'people') ?? [], ['is_owner' => false])
            ->syncRelation($product, 'parties', Arr::get($validated, 'parties') ?? [], ['is_owner' => false]);

        $product->people()->attach($request->user()->person, ['is_owner' => true]);

        return ProductResource::make(
            $this->productRepository->show($product->getKey())
        );
    }

    public function download($id): Responsable
    {
        return $this->productRepository->findOrFail($id)
            ->getFirstMedia(MediaCollections::PRODUCT_OBJECT);
    }
}
