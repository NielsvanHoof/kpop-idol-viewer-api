<?php

namespace App\Http\Controllers;

use App\Http\Resources\IdolResource;
use App\Models\Idol;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IdolController extends Controller
{
    public function index(Request $request)
    {
        $idols = QueryBuilder::for(Idol::class)
            ->allowedFilters(['name', 'group_id', AllowedFilter::scope('search', 'whereScout')])
            ->allowedSorts('name', 'group', 'debute_date')
            ->allowedIncludes('group', 'schedules', 'group.schedules')
            ->cursorPaginate(
                perPage: $request->input('per_page', 50),
            );

        return IdolResource::collection($idols);
    }

    public function show(Idol $idol)
    {
        $idol->load('group', 'schedules', 'group.schedules');

        return new IdolResource($idol);
    }
}
