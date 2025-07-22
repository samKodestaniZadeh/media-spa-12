<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fileable_id'=> $this->faker->numberBetween(1,10),
            'fileable_type'=> Product::class,
            'url'=> 'files/logo.jpg',
            'status' =>4,
        ];
    }
}
