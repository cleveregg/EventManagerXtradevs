<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        // Future events for user 1
        Event::factory(5)->for($user1)->create();
        Event::factory(2)->for($user1)->smallLimit()->create();

        // Future events for user 2
        Event::factory(5)->for($user2)->create();
        Event::factory(2)->for($user2)->smallLimit()->create();

        // Past events (for testing "future only" filter)
        Event::factory(2)->for($user1)->past()->create();
        Event::factory(2)->for($user2)->past()->create();
    }
}
