<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,10),
            'parent_id' => $this->faker->numberBetween(0,10),
            'commentable_type'=> Product::class,
            'commentable_id' => $this->faker->numberBetween(1,10),
            'text' => $this->faker->realText(200),
            'status' => $this->faker->numberBetween(0,1),
        ];
    }
}
