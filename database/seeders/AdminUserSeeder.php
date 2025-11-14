<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'admin@example.com';
        $password = 'secret123';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make($password),
                'role' => 'admin'
            ]
        );

        // echo credentials to console when seeding
        $this->command->info("Admin user ensured: {$email} / {$password}");
    }
}
