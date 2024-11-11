<?php

namespace App\Http\Resources;

use App\Models\Idol;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/** @mixin Idol */
class IdolResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'stage_name' => $this->stage_name,
            'birthdate' => $this->birthdate,
            'nationality' => $this->nationality,
            'debute_date' => $this->debute_date,
            'slug' => $this->slug,
            'position' => $this->position,
            'social_media' => $this->social_media,
            'bio' => $this->bio,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'spotify_id' => $this->spotify_id,

            'profile_picture' => $this->getMedia('profile_photo')->first()?->getUrl(),

            'photos' => $this->getMedia('photos')->map(fn(Media $photo) => $photo->getUrl()),

            'group_id' => $this->group_id,

            'group' => new GroupResource($this->whenLoaded('group')),
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
