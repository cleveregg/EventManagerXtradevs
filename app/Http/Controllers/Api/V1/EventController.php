<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        $events = $this->eventService->getUpcomingEvents();

        return EventResource::collection($events);
    }

    public function show(int $id)
    {
        try {
            $event = $this->eventService->findEvent($id);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        return new EventResource($event);
    }
}
