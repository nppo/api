<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Action;
use App\Enumerators\MediaCollections;
use App\Enumerators\TagTypes;
use App\Http\Requests\PersonStoreRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use App\Models\User;
use App\Repositories\PersonRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Way2Web\Force\Http\Controller;

class PersonController extends Controller
{
    private PersonRepository $personRepository;
    private UserRepository $userRepository;

    public function __construct(PersonRepository $personRepository, UserRepository $userRepository)
    {
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;

        $this
            ->protectActionRoutes(['api']);
    }

    public function index(): AnonymousResourceCollection
    {
        return PersonResource::collection(
            $this->personRepository->all()
        );
    }

    /** @param mixed $id */
    public function show($id): PersonResource
    {
        return PersonResource::make(
            $this->personRepository->show($id)
        )
            ->withPermissions();
    }

    public function store(PersonStoreRequest $request): PersonResource
    {
        $this->authorize('create', Person::class);

        /** @var User */
        $user = $this->userRepository->findOrFail($request->user()->id);

        $validated = $request->validated();

        $person = $this
            ->personRepository
            ->createFull(
                Arr::except($validated, ['profile_picture', 'skills', 'themes']),
                $user
            );

        if ($request->hasFile('profile_picture')) {
            $person
                ->addMediaFromRequest('profile_picture')
                ->preservingOriginal()
                ->toMediaCollection(MediaCollections::PROFILE_PICTURE);
        }

        $person->syncTags(
            Collection::make(Arr::get($validated, 'skills') ?? [])
                ->map(fn ($tag) => $tag['label'])
                ->toArray(),
            TagTypes::SKILL,
        );

        $person->syncTags(
            Collection::make(Arr::get($validated, 'themes') ?? [])
                ->map(fn ($tag) => $tag['label'])
                ->toArray(),
            TagTypes::THEME,
            true
        );

        return PersonResource::make(
            $this->personRepository->show($person->getKey())
        );
    }

    public function update(PersonUpdateRequest $request, string $id): PersonResource
    {
        /** @var Person $person */
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
                ->map(fn ($tag) => $tag['label'])
                ->toArray(),
            TagTypes::SKILL,
        );

        $person->syncTags(
            Collection::make(Arr::get($validated, 'themes') ?? [])
                ->map(fn ($tag) => $tag['label'])
                ->toArray(),
            TagTypes::THEME,
            true
        );

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
