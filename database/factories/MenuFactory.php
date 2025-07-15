<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' =>  $this->faker->numberBetween(1,10),
            'section' =>  'support',
            'item' => 'support-create',
            'product'=> 0,
            'sub'=> 0,
            'parent_id'=> $this->faker->numberBetween(1,10),
            'name'=> 'پشتیبانی محصول',
            'status' => $this->faker->numberBetween(0,1),
        ];
    }
}
