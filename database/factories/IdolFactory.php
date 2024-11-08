<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Idol;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IdolFactory extends Factory
{
    protected $model = Idol::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'stage_name' => $this->faker->name(),
            'birthdate' => Carbon::now(),
            'nationality' => $this->faker->word(),
            'debute_date' => Carbon::now(),
            'position' => $this->faker->word(),
            'social_media' => $this->faker->words(),
            'slug' => $this->faker->slug(),
            'bio' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'group_id' => Group::factory(),
        ];
    }
}
