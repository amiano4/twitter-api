<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Following>
 */
class FollowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'follower' => User::inRandomOrder()->first()->id,
            'followed_user' => function (array $attributes) {
                return User::where('id', '!=', $attributes['follower'])->inRandomOrder()->first()->id;
            },
        ];
    }
}