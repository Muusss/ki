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
        // Admin Cafe
        User::firstOrCreate(
            ['email' => 'admin@cafe.com'],
            [
                'name' => 'Admin Cafe',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
