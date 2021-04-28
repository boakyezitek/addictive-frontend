<?php

namespace Database\Factories;

use App\Models\AudioBook;
use Illuminate\Database\Eloquent\Factories\Factory;

class AudioBookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AudioBook::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->catchPhrase,
            'description' => $this->faker->sentence,
            'e_number' => $this->faker->numberBetween(100000000, 999999999),
            'internal_code' => $this->faker->numberBetween(100000000, 999999999),
            'isbn' => $this->faker->numberBetween(100000000, 999999999),
            'recording_studio' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 0, 100),
            'language_id' => 1,
            'view_count' => $this->faker->numberBetween(0, 10000),
            'publication_date' => $this->faker->dateTime,
            'created_at' => '2020-11-03 12:00:00',
            'updated_at' => '2020-11-03 12:00:00'
        ];
    }
}
