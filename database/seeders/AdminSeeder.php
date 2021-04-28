<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'email' => 'admin@appsolute.fr',
            'password' => bcrypt('admin'),
            'first_name' => 'Admin',
            'last_name' => 'Test'
        ]);
    }
}
