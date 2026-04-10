<?php

namespace Tests\Feature;

use App\Mail\NewRegistrationNotification;
use App\Mail\RegistrationConfirmation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_queues_confirmation_and_notification_emails(): void
    {
        Mail::fake();

        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $creator->id,
            'attendee_limit' => 10,
        ]);
        $registrant = User::factory()->create();

        $this->actingAs($registrant)->post(route('events.register', $event));

        Mail::assertQueued(RegistrationConfirmation::class, function ($mail) use ($registrant) {
            return $mail->hasTo($registrant->email);
        });

        Mail::assertQueued(NewRegistrationNotification::class, function ($mail) use ($creator) {
            return $mail->hasTo($creator->email);
        });
    }

    public function test_no_emails_queued_when_registration_fails(): void
    {
        Mail::fake();

        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $creator->id,
            'attendee_limit' => 0,
        ]);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('events.register', $event));

        Mail::assertNotQueued(RegistrationConfirmation::class);
        Mail::assertNotQueued(NewRegistrationNotification::class);
    }
}
