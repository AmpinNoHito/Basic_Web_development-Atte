<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\facades\Hash;
use Faker\Generator;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Generator::class);
        for ($i = 1; $i <= 80; $i++) {     
            User::create([
                'name' => $faker->name(),
                'email' => 'test'.$i.'@ex.com',
                'password' => Hash::make('password'.$i),
            ]);
        }
    }
}
