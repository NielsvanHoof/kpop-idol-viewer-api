<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Spatie\QueryBuilder\QueryBuilder;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = QueryBuilder::for(Schedule::class)
            ->allowedFilters('date', 'time')
            ->allowedSorts('date', 'time')
            ->allowedIncludes('idol', 'group')
            ->paginate();

        return ScheduleResource::collection($schedules);
    }

    public function show(Schedule $schedule)
    {
        $schedule->load('idol', 'group');

        return new ScheduleResource($schedule);
    }
}
