<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words($nb = 3, $asText = true);
        return [
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->slug,
            'parent_id' => 0,
        ];
    }
}
