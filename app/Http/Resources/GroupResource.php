<?php

namespace App\Http\Resources;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/** @mixin Group */
class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'debut_date' => $this->debut_date,
            'company' => $this->company,
            'bio' => $this->bio,
            'social_media' => $this->social_media,
            'slug' => $this->slug,
            'spotify_id' => $this->spotify_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
 
            'cover_picture' => $this->getMedia('cover_images')->first()?->getUrl(),

            'photos' => $this->getMedia('photos')->map(fn(Media $photo) => $photo->getUrl()),

            'idols' => IdolResource::collection($this->whenLoaded('idols')),
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
