<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
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
            'name' => $this->faker->jobTitle(),
            'info' => $this->faker->text(),
        ];
    }
}
