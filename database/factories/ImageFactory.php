<?php

namespace Database\Factories;

use App\Models\Tarahi;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,50),
            'url' => 'images/logo.jpg',
            'status' =>4,// $this->faker->numberBetween(4),
            'imageable_type'=>Product::class,
            'imageable_id'=>  $this->faker->numberBetween(1,100),
        ];
    }
}
