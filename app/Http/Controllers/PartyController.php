<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
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

    /** @param mixed $id */
    public function show($id): PartyResource
    {
        return PartyResource::make(
            $this->partyRepository->show($id)
        )
            ->withPermissions();
    }
}
