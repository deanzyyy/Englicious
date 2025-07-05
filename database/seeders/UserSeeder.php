<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'Englicious@admin.com'],
            [
                'name' => 'Admin Englicious',
                'password' => bcrypt('englicious'),
                'role' => 'admin'
            ]
        );
    }
} 