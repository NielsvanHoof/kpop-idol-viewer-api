<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    public function index()
    {
        $groups = QueryBuilder::for(Group::class)
            ->allowedFilters('name', 'debut_date', 'company')
            ->allowedSorts('name', 'debut_date', 'company')
            ->allowedIncludes('idols', 'schedules')
            ->paginate();

        return GroupResource::collection($groups);
    }

    public function show(Group $group)
    {
        $group->load('idols', 'schedules');

        return new GroupResource($group);
    }
}
