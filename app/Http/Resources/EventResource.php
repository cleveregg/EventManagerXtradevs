<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'attendee_limit' => $this->attendee_limit,
            'registered_count' => $this->registrations_count ?? 0,
            'available_spots' => $this->attendee_limit - ($this->registrations_count ?? 0),
            'image_url' => $this->image ? url('storage/' . $this->image) : null,
            'creator' => $this->user?->name,
            'created_at' => $this->created_at,
        ];
    }
}
