<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BankFactory extends Factory
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
            'bank_name'=> $this->faker->realText(10),
            'account_name' => $this->faker->realText(10),
            'account_number'=> $this->faker->numberBetween(10000000000,90000000000),
            'cart_number' => $this->faker->numberBetween(1000000000000000,9000000000000000),
            'shaba_number'=> $this->faker->numberBetween(100000000000000000000000,900000000000000000000000),
        ];
    }
}
