<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraphs(2, true),
            'date' => fake()->dateTimeBetween('+1 week', '+6 months'),
            'attendee_limit' => fake()->numberBetween(5, 100),
            'image' => null,
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    public function smallLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'attendee_limit' => fake()->numberBetween(1, 3),
        ]);
    }
}
