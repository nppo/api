<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\MediaCollections;
use App\Http\Requests\PersonUpdateRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Repositories\MediaRepository;
use App\Repositories\PersonRepository;
use Illuminate\Support\Arr;

class PersonController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository, MediaRepository $mediaRepository)
    {
        $this->personRepository = $personRepository;
        $this->mediaRepository = $mediaRepository;
    }

    public function show($id): PersonResource
    {
        return new PersonResource(
            $this->personRepository->show($id)
        );
    }

    public function update(PersonUpdateRequest $request, $id)
    {
        $this
            ->personRepository
            ->update(
                Arr::except($request->validated(), ['profile_picture']),
                $id
            );

        if ($request->hasFile('profile_picture')) {
            /** @var Person $person */
            $person = $this->personRepository->findOrFail($id);

            $person
                ->addMediaFromRequest('profile_picture')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PROFILE_PICTURE);
        }

        return new PersonResource(
            $this->personRepository->show($id)
        );
    }
}
