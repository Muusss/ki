<?php

namespace Database\Seeders;

use App\Models\SubKriteria;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KriteriaSeeder::class,
            AlternatifSeeder::class,
            SubKriteriaSeeder::class,
        ]);
    }
}