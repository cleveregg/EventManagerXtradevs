<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_edit_another_users_event(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->get(route('events.edit', $event));

        $response->assertForbidden();
    }

    public function test_user_cannot_update_another_users_event(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->put(route('events.update', $event), [
            'name' => 'Hacked Event Name',
            'date' => now()->addWeek()->toDateTimeString(),
            'attendee_limit' => 10,
        ]);

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_event(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $userB->id]);

        $response = $this->actingAs($userA)->delete(route('events.destroy', $event));

        $response->assertForbidden();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'deleted_at' => null]);
    }

    public function test_user_can_edit_own_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('events.edit', $event));

        $response->assertOk();
    }

    public function test_user_can_update_own_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('events.update', $event), [
            'name' => 'Updated Event Name',
            'date' => now()->addMonth()->toDateTimeString(),
            'attendee_limit' => 20,
        ]);

        $response->assertRedirect(route('events.show', $event));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event Name',
        ]);
    }

    public function test_user_can_delete_own_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('events.destroy', $event));

        $response->assertRedirect(route('events.index'));
        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }
}
