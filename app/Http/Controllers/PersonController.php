<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PersonResource;
use App\Repositories\PersonRepository;

class PersonController extends Controller
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function show($id): PersonResource
    {
        return new PersonResource(
            $this->personRepository->show($id)
        );
    }
}
