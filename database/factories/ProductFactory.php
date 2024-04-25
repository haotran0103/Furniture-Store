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
        $productName = $this->faker->unique()->words(3, true); // Tạo tên sản phẩm ngẫu nhiên
        return [
            'name' => $productName,
            'slug' => Str::slug($productName),
            'price' => $this->faker->numberBetween(1000, 100000),
            'feature_image' => json_encode([
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
                $this->faker->imageUrl(),
                $this->faker->imageUrl() // Thêm ảnh giả bằng Faker
            ]),
            'content' => $this->faker->paragraph,
            'quantity' => $this->faker-> numberBetween(10, 1000),
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id; // Giả sử bạn đã có User
            },
            'category_id' => function () {
                return \App\Models\Category::inRandomOrder()->first()->id; // Giả sử bạn đã có Category
            }
        ];
    }
}
