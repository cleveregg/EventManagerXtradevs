<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventService
{
    public function getUpcomingEvents(int $perPage = 9): LengthAwarePaginator
    {
        return Event::where('date', '>', now())
            ->with('user')
            ->withCount('registrations')
            ->orderBy('date', 'asc')
            ->paginate($perPage);
    }

    public function findEvent(int $id): Event
    {
        return Event::withCount('registrations')
            ->with('user')
            ->findOrFail($id);
    }
}
