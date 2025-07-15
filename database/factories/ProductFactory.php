<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
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
            'slug' => $this->faker->name(1),
            'name' => $this->faker->name(1),
            'name_en' => Str::random(10),
            'text' => $this->faker->realText(50),
            'demo_link' => $this->faker->paragraph(1),
            'price' => $this->faker->numberBetween(10000,100000),
            'version' => $this->faker->randomNumber(1),
            'status' => 4
        ];
    }
}
