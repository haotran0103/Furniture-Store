<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Bạn có thể sử dụng bcrypt để mã hóa mật khẩu
            'remember_token' => Str::random(10),
            'role' => 'admin', // hoặc 'admin' tùy theo cần
            'fullname' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function rootUser(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => 'root@gmail.com',
                'password' => Hash::make('12341234'), // Mã hóa mật khẩu
                'role' => 'admin',
                'fullname' => 'Trần Quốc Hào',
                'address' => 'Tự do',
                'phone' => '0123456789', // Giả sử số điện thoại là 0123456789
            ];
        });
    }
}
