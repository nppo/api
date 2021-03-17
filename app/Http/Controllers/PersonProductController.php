<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Repositories\PersonRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Way2Web\Force\Http\Controller;

class PersonProductController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index($id): AnonymousResourceCollection
    {
        return ProductResource::collection(
            $this->personRepository->findOrFail($id)->products
        );
    }
}
