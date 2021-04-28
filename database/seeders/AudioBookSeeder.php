<?php

namespace Database\Seeders;

use App\Models\AudioBook;
use Illuminate\Database\Seeder;

class AudioBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AudioBook::factory()->times(25)->create();
    }
}
