<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class UserDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'country' => $this->faker->countryCode(),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'citizenship' => $this->faker->countryCode(),
            'id_prefix' => 'V-',
            'id_number' => $this->faker->randomNumber(8, true),
            'occupation' => $this->faker->jobTitle(),
            'gender' => 'male',
            'birth_at' => $this->faker->date(),
            'birthplace' => $this->faker->city(),
            'number' => $this->faker->randomNumber(2, true),
            'rank' => $this->faker->numberBetween(10, 13),
            'member_since' => $this->faker->date(),
            'mask' => $this->faker->boolean(),
            'godfather' => null,
            'zunftrat_in' => $this->faker->date(),
            'zunftrat_out' => $this->faker->date(),
            'status' => $this->faker->numberBetween(1, 4),
            'avatar' => '/storage/factory/avatar/misc/avatar-user.jpg',
        ];

    }
}
