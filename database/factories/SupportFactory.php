<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupportFactory extends Factory
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
            'parent_id' => $this->faker->numberBetween(1,50),
            'destination' => $this->faker->numberBetween(1,10),
            'menu' => $this->faker->realText(10),
            'recepiant' => $this->faker->realText(10),
            'subject' => $this->faker->realText(10),
            'text' => $this->faker->realText(200),
            'status' => $this->faker->numberBetween(0,1),
        ];
    }
}
