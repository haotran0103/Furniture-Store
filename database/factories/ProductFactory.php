<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'price' => $this->faker->numberBetween(100, 1000),
            'feature_image' => $this->faker->imageUrl(),
            'content' => $this->faker->text,
            'user_id' => $this->faker->numberBetween(1, 10), // Số người dùng có thể thay đổi tùy theo cần
            'category_id' => $this->faker->numberBetween(1, 5), // Số danh mục có thể thay đổi tùy theo cần
            'slug' => $this->faker->unique()->slug,
        ];
    }
}
