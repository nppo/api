<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Party\StoreRequest;
use App\Http\Requests\Party\UpdateRequest;
use App\Http\Resources\PartyResource;
use App\Models\Party;
use App\Repositories\PartyRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartyController extends Controller
{
    private PartyRepository $partyRepository;

    public function __construct(PartyRepository $personRepository)
    {
        $this->partyRepository = $personRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return PartyResource::collection(
            $this->partyRepository->index()
        );
    }

    public function show(string $id): PartyResource
    {
        return PartyResource::make(
            $this->partyRepository->show($id)
        )
            ->withPermissions();
    }

    public function store(StoreRequest $storeRequest): PartyResource
    {
        $this->authorize('create', Party::class);

        $party = $this->partyRepository->createFull($storeRequest->validated());

        return PartyResource::make($party);
    }

    public function update(string $id, UpdateRequest $updateRequest): PartyResource
    {
        $party = $this->partyRepository->findOrFail($id);

        $this->authorize('update', $party);

        $party = $this->partyRepository->updateFull($id, $updateRequest->validated());

        return PartyResource::make($party);
    }

    public function destroy(string $id): PartyResource
    {
        $party = $this->partyRepository->findOrFail($id);

        $this->authorize('delete', $party);

        $party = $this->partyRepository->deleteFull($id);

        return PartyResource::make($party);
    }
}
