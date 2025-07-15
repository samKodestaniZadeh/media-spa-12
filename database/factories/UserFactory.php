<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_name' => 'سام_کردستانی',
            'name' => 'سام',
            'name_show' => 'فروشگاه مدیا',
            'lasst_name' => 'کردستانی',
            'tel' => null,
            'email' => 'mediastorertl@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$M5KHQigx7TOlmNGb8/7QXOInKYiR0hkHjtuDPpDLBXwJmxwcmMZpi', // password
            'remember_token' => null,
            'status'=>0,
            'person'=> 0,
        ];
    }
}
