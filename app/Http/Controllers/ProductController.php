<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $this->productRepository
                ->makeQuery()
                ->orderByDesc('id')
                ->get()
        );
    }
}
