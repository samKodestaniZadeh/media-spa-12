<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' =>  Str::random(8),
            'percent'=> $this->faker->numberBetween(5,20),
            'couponable_type'=> Product::class,
            'couponable_id' => $this->faker->numberBetween(1,10),
        ];
    }
}
