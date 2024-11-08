<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Idol;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'date' => Carbon::now(),
            'location' => $this->faker->word(),
            'reminder' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'idol_id' => Idol::factory(),
            'group_id' => Group::factory(),
            'created_by' => User::factory(),
        ];
    }
}
