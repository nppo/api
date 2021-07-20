<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LikeStoreRequest;
use App\Http\Resources\LikeResource;
use App\Models\Person;
use App\Repositories\PersonRepository;
use Way2Web\Force\Http\Controller;

class PersonLikeController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function index($id): LikeResource
    {
        /** @var Person $person */
        $person = $this->personRepository->findOrFail($id);

        return LikeResource::make($person->user);
    }

    public function store($personId, LikeStoreRequest $request): LikeResource
    {
        $person = $this->personRepository->addLike(
            $personId,
            $request->getLikableType(),
            $request->getLikableId()
        );

        return LikeResource::make($person->user);
    }
}
