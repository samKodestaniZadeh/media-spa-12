<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' =>$this->faker->numberBetween(1,10),
            'discountable_id' =>$this->faker->numberBetween(1,10),
            'discountable_type' => Product::class,
            'expired' => new Carbon('+1 days'),
            'percent' => $this->faker->numberBetween(1,30),
        ];
    }
}
