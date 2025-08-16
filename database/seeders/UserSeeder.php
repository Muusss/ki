<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds untuk user default
     */
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@skincare.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Regular user untuk testing
        User::firstOrCreate(
            ['email' => 'user@skincare.com'],
            [
                'name' => 'User Test',
                'password' => Hash::make('user123'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
