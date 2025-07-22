<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarahiFactory extends Factory
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
            'title' => $this->faker->title(1),
            'text' => $this->faker->realText(50),
            'price' => $this->faker->numberBetween(10000,100000),
            'status' => $this->faker->numberBetween(0,4),
            'slug'=> Str::random(10),
            'expired_at'=>new Carbon(),
        ];
    }
}
