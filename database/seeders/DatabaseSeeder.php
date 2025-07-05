<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create default admin user
        User::factory()->create([
            'name' => 'Admin Englicious',
            'email' => 'Englicious@admin.com',
            'password' => bcrypt('englicious'),
            'role' => 'admin'
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'teacher'
        ]);

        // Run Topic and Subtopic seeder
        $this->call([
            TopicSeeder::class,
        ]);

        // $this->call(UserSeeder::class);
    }
}
