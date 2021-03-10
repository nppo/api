<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Repositories\PartyRepository;

class PartyController extends Controller
{
    private PartyRepository $partyRepository;

    public function __construct(PartyRepository $personRepository)
    {
        $this->partyRepository = $personRepository;
    }

    public function show($id): PartyResource
    {
        return PartyResource::make(
            $this->partyRepository->show($id)
        )
            ->withPermissions();
    }
}
