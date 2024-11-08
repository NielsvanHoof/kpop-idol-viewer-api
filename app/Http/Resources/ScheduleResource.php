<?php

namespace App\Http\Resources;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Schedule */
class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'reminder' => $this->reminder,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type' => $this->type,

            'idol_id' => $this->idol_id,
            'group_id' => $this->group_id,
            'created_by' => $this->created_by,

            'idol' => new IdolResource($this->whenLoaded('idol')),
            'group' => new GroupResource($this->whenLoaded('group')),
        ];
    }
}
