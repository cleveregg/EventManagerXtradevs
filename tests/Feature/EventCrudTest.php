<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_event(): void
    {
        $response = $this->get(route('events.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_event(): void
    {
        $response = $this->post(route('events.store'), []);

        $response->assertRedirect(route('login'));
    }

    public function test_validation_fails_with_missing_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), []);

        $response->assertSessionHasErrors(['name', 'date', 'attendee_limit']);
    }

    public function test_validation_fails_with_short_name(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'name' => 'Ab',
            'date' => now()->addDay()->toDateTimeString(),
            'attendee_limit' => 10,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_validation_fails_with_past_date(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'name' => 'Valid Event Name',
            'date' => now()->subDay()->toDateTimeString(),
            'attendee_limit' => 10,
        ]);

        $response->assertSessionHasErrors(['date']);
    }

    public function test_validation_fails_with_zero_attendee_limit(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'name' => 'Valid Event Name',
            'date' => now()->addDay()->toDateTimeString(),
            'attendee_limit' => 0,
        ]);

        $response->assertSessionHasErrors(['attendee_limit']);
    }

    public function test_successful_creation_stores_event_and_redirects(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'name' => 'Laravel Meetup Budapest',
            'date' => now()->addWeek()->toDateTimeString(),
            'description' => 'A great event for developers.',
            'attendee_limit' => 50,
            'image' => UploadedFile::fake()->image('event.jpg'),
        ]);

        $response->assertRedirect(route('events.index'));
        $response->assertSessionHas('success', 'Esemény sikeresen létrehozva!');

        $this->assertDatabaseHas('events', [
            'name' => 'Laravel Meetup Budapest',
            'user_id' => $user->id,
            'attendee_limit' => 50,
        ]);

        Storage::disk('public')->assertExists('events/' . Event::first()->image_name ?? '');
    }

    public function test_successful_creation_without_image(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('events.store'), [
            'name' => 'Laravel Meetup Budapest',
            'date' => now()->addWeek()->toDateTimeString(),
            'attendee_limit' => 50,
        ]);

        $response->assertRedirect(route('events.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'name' => 'Laravel Meetup Budapest',
            'user_id' => $user->id,
        ]);
    }
}
