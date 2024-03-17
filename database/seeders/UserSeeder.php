<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Root User',
            'email' => 'root@gmail.com',
            'password' => bcrypt('123123123'),
            'role' => 'root', // Thay đổi role nếu cần
        ]);
    }
}
