<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function __construct(
        private readonly EventService $eventService,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Events/Index', [
            'events' => $this->eventService->getUpcomingEvents(9),
        ]);
    }
}
