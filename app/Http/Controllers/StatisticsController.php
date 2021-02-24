<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enumerators\Entities;
use App\Http\Resources\EntityStatisticsResource;

class StatisticsController extends Controller
{
    public function entities(): EntityStatisticsResource
    {
        return new EntityStatisticsResource(
            (object) ['entities' => collect(Entities::asArray())]
        );
    }
}
