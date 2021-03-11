<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Action;
use App\Enumerators\MediaCollections;
use App\Http\Requests\PersonUpdateRequest;
use App\Http\Resources\PersonResource;
use App\Repositories\MediaRepository;
use App\Repositories\PersonRepository;
use Illuminate\Support\Arr;
use Way2Web\Force\Http\Controller;

class PersonController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository, MediaRepository $mediaRepository)
    {
        $this->personRepository = $personRepository;
        $this->mediaRepository = $mediaRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function show($id): PersonResource
    {
        return PersonResource::make(
            $this->personRepository->show($id)
        )
            ->withPermissions();
    }

    public function update(PersonUpdateRequest $request, $id)
    {
        $person = $this->personRepository->findOrFail($id);

        $this->authorize(Action::UPDATE, $person);

        $this
            ->personRepository
            ->update(
                Arr::except($request->validated(), ['profile_picture']),
                $id
            );

        if ($request->hasFile('profile_picture')) {
            $person
                ->addMediaFromRequest('profile_picture')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PROFILE_PICTURE);
        }

        return PersonResource::make(
            $this->personRepository->show($id)
        )
            ->withPermissions();
    }
}
