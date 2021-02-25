<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Repositories\PersonRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PersonController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return PersonResource::collection(
            $this->personRepository->index()
        );
    }
}
