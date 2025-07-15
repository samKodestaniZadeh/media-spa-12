<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class IdentityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>1,
            'national_code'=> null,
            'national_id'=> null,
            'economical_number'=>null,
            'status'=>0,
        ];
    }
}
