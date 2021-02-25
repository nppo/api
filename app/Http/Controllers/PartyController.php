<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Repositories\PartyRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartyController extends Controller
{
    private PartyRepository $partyRepository;

    public function __construct(PartyRepository $partyRepository)
    {
        $this->partyRepository = $partyRepository;
    }

    public function index(): AnonymousResourceCollection
    {
        return PartyResource::collection(
            $this->partyRepository->index()
        );
    }
}
