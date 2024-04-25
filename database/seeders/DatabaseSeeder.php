<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'root@gmail.com',
            'password' => Hash::make('12341234'), // Mã hóa mật khẩu
            'role' => 'admin',
            'fullname' => 'Trần Quốc Hào',
            'address' => 'Tự do',
            'phone' => '0123456789', // Giả sử số điện thoại là 0123456789
            'remember_token' => Str::random(10),
        ]);
    }
}
