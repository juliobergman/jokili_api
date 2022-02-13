<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NomineeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'election_id' => 1,
        'position_id' => 1,
        'user_id' => 1,
        ];
    }
}
