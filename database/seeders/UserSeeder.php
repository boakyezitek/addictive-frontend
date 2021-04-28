<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'username' => 'Alexandre',
            'email' => 'alexandre.p@appsolute.fr',
            'terms_accepted_at' => now(),
            'password' => bcrypt('Alex_2_Appsolute!'), // password
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
        factory(\App\Models\User::class, 10);
    }
}
