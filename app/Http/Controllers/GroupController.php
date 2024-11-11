<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = QueryBuilder::for(Group::class)
            ->allowedFilters('name', 'debut_date', 'company', AllowedFilter::scope('search', 'whereScout'))
            ->allowedSorts('name', 'debut_date', 'company')
            ->allowedIncludes('idols', 'schedules')
            ->cursorPaginate(
                $request->input('per_page', 50),
            );

        return GroupResource::collection($groups);
    }

    public function show(Group $group)
    {
        $group->load('idols', 'schedules');

        return new GroupResource($group);
    }
}
