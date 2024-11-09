<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdolResource;
use App\Models\Idol;

class RandomIdolController extends Controller
{
    public function __invoke()
    {
        $idols = Idol::query()
            ->inRandomOrder()
            ->get();

        return IdolResource::collection($idols);
    }
}
