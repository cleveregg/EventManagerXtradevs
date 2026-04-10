<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_only_future_events(): void
    {
        $user = User::factory()->create();

        Event::factory()->count(3)->create([
            'user_id' => $user->id,
            'date' => now()->addWeek(),
        ]);

        Event::factory()->count(2)->past()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/v1/events');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_api_returns_paginated_events(): void
    {
        $user = User::factory()->create();

        Event::factory()->count(15)->create([
            'user_id' => $user->id,
            'date' => now()->addWeek(),
        ]);

        $response = $this->getJson('/api/v1/events');

        $response->assertOk();
        $response->assertJsonCount(9, 'data');
        $response->assertJsonPath('meta.last_page', 2);
        $response->assertJsonPath('meta.total', 15);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'date',
                    'attendee_limit',
                    'registered_count',
                    'available_spots',
                    'image_url',
                    'creator',
                    'created_at',
                ],
            ],
            'links',
            'meta',
        ]);
    }

    public function test_api_returns_correct_json_structure_for_single_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'date' => now()->addWeek(),
            'attendee_limit' => 50,
        ]);

        // Add some registrations
        $registrants = User::factory()->count(3)->create();
        foreach ($registrants as $registrant) {
            Registration::create([
                'user_id' => $registrant->id,
                'event_id' => $event->id,
            ]);
        }

        $response = $this->getJson("/api/v1/events/{$event->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'date',
                'attendee_limit',
                'registered_count',
                'available_spots',
                'image_url',
                'creator',
                'created_at',
            ],
        ]);
        $response->assertJsonPath('data.id', $event->id);
        $response->assertJsonPath('data.registered_count', 3);
        $response->assertJsonPath('data.available_spots', 47);
        $response->assertJsonPath('data.creator', $user->name);
    }

    public function test_api_returns_404_for_nonexistent_event(): void
    {
        $response = $this->getJson('/api/v1/events/99999');

        $response->assertNotFound();
        $response->assertJson(['message' => 'Event not found.']);
    }

    public function test_past_events_are_excluded_from_listing(): void
    {
        $user = User::factory()->create();

        Event::factory()->past()->create([
            'user_id' => $user->id,
            'name' => 'Past Event',
        ]);

        Event::factory()->create([
            'user_id' => $user->id,
            'name' => 'Future Event',
            'date' => now()->addMonth(),
        ]);

        $response = $this->getJson('/api/v1/events');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'Future Event');
    }
}
