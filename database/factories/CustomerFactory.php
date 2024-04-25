<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'password' => bcrypt('password'), 
            'city' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
        ];
    }
}
