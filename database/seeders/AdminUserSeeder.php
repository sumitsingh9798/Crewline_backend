<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create the initial Crewline Admin.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@crewline.com',
            ],
            [
                'name' => 'Crewline Admin',
                'password' => 'Admin@123456',
                'role' => 'admin',
            ]
        );

        $this->command->info(
            'Initial Admin created successfully.'
        );
    }
}