<?php

namespace App\Services;

use App\Exceptions\AlreadyRegisteredException;
use App\Exceptions\EventFullException;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    public function registerUserForEvent(User $user, Event $event): Registration
    {
        return DB::transaction(function () use ($user, $event) {
            $event = Event::lockForUpdate()->find($event->id);

            $currentCount = $event->registrations()->count();

            if ($currentCount >= $event->attendee_limit) {
                throw new EventFullException();
            }

            if ($event->registrations()->where('user_id', $user->id)->exists()) {
                throw new AlreadyRegisteredException();
            }

            return Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
            ]);
        });
    }
}
