<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word(),
            'price'       => $this->faker->randomFloat(2, 10000, 500000),
            'description' => $this->faker->sentence(10),
            'image'       => $this->faker->imageUrl(640, 480, 'products', true),
            'categories'  => $this->faker->randomElement(['Elektronik', 'Fashion', 'Makanan', 'Minuman']),
            'favourite'   => $this->faker->boolean(),
            'user_id'     => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
