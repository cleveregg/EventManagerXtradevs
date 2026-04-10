<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\AlreadyRegisteredException;
use App\Exceptions\EventFullException;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\RegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registrationService,
    ) {}

    public function store(Event $event): RedirectResponse
    {
        try {
            $this->registrationService->registerUserForEvent(Auth::user(), $event);

            return redirect()->back()->with('success', 'Sikeresen jelentkeztél az eseményre!');
        } catch (EventFullException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (AlreadyRegisteredException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
