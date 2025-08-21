<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutorial>
 */
class TutorialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(5),
            'link'        => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'thumbnail'   => $this->faker->imageUrl(640, 480, 'tutorials', true),
            'user_id'     => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
