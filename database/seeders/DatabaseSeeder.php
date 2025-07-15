<?php

namespace Database\Seeders;

use App\Models\Identity;
use App\Models\User;
use App\Models\Product;
use App\Models\Image;
use App\Models\Profile;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Tarahi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create();
        Role::factory(4)->create();
        RoleUser::factory(1)->create();
        Identity::factory(1)->create();
        Profile::factory(1)->create();
    }
}
