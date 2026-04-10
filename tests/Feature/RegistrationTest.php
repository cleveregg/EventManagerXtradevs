<?php

namespace Tests\Feature;

use App\Exceptions\AlreadyRegisteredException;
use App\Exceptions\EventFullException;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use App\Services\RegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_for_event_with_available_spots(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['attendee_limit' => 10]);

        $response = $this->actingAs($user)->post(route('events.register', $event));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    public function test_user_cannot_register_twice_for_same_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['attendee_limit' => 10]);

        Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user)->post(route('events.register', $event));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_user_cannot_register_for_full_event(): void
    {
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $creator->id,
            'attendee_limit' => 1,
        ]);

        $existingUser = User::factory()->create();
        Registration::create([
            'user_id' => $existingUser->id,
            'event_id' => $event->id,
        ]);

        $newUser = User::factory()->create();
        $response = $this->actingAs($newUser)->post(route('events.register', $event));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_guest_cannot_register_for_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->post(route('events.register', $event));

        $response->assertRedirect(route('login'));
    }

    public function test_race_condition_only_one_registration_succeeds_when_one_spot_left(): void
    {
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $creator->id,
            'attendee_limit' => 1,
        ]);

        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $service = app(RegistrationService::class);

        // First registration should succeed
        $service->registerUserForEvent($userA, $event);

        // Second registration should fail with EventFullException
        $this->expectException(EventFullException::class);
        $service->registerUserForEvent($userB, $event);

        $this->assertDatabaseCount('registrations', 1);
    }

    public function test_race_condition_duplicate_registration_throws_exception(): void
    {
        $event = Event::factory()->create(['attendee_limit' => 10]);
        $user = User::factory()->create();

        $service = app(RegistrationService::class);

        $service->registerUserForEvent($user, $event);

        $this->expectException(AlreadyRegisteredException::class);
        $service->registerUserForEvent($user, $event);

        $this->assertDatabaseCount('registrations', 1);
    }
}
