<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Action;
use App\Enumerators\MediaCollections;
use App\Enumerators\TagTypes;
use App\Http\Requests\PersonUpdateRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Repositories\MediaRepository;
use App\Repositories\PersonRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
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

    public function index(): AnonymousResourceCollection
    {
        return PersonResource::collection(
            $this->personRepository->all()
        );
    }

    public function show($id): PersonResource
    {
        return PersonResource::make(
            $this->personRepository->show($id)
        )
            ->withPermissions();
    }

    public function update(PersonUpdateRequest $request, $id): PersonResource
    {
        /** @var Person */
        $person = $this->personRepository->findOrFail($id);

        $this->authorize(Action::UPDATE, $person);

        $validated = $request->validated();

        $this
            ->personRepository
            ->update(
                Arr::except($request->validated(), ['profile_picture', 'skills', 'themes', 'meta']),
                $id
            );

        if ($request->hasFile('profile_picture')) {
            $person
                ->addMediaFromRequest('profile_picture')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PROFILE_PICTURE);
        }

        $person->syncTags(
            Collection::make(Arr::get($validated, 'skills') ?? [])
                ->map(fn ($skill) => $skill['label'])
                ->toArray(),
            TagTypes::SKILL,
        );

        $this->syncRelation($person, 'themes', Arr::get($validated, 'themes') ?: []);

        $person
            ->syncMeta(
                Collection::make(Arr::get($validated, 'meta') ?? [])
                    ->map(function (array $data): array {
                        return [
                            'attribute_id' => $data['id'],
                            'value'        => $data['value'],
                        ];
                    })
            );

        return PersonResource::make(
            $this->personRepository->show($id)
        )
            ->withPermissions();
    }
}
