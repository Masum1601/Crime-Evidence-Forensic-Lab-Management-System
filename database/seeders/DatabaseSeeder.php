<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\CaseModel;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['role_name' => 'Admin']);
        $officer = Role::create(['role_name' => 'Officer']);
        $analyst = Role::create(['role_name' => 'Analyst']);
        $userRole = Role::create(['role_name' => 'User']);

        $officerUser = User::create([
            'role_id' => $officer->role_id,
            'full_name' => 'Officer John Doe',
            'email' => 'officer.john@cefl.test',
            'password' => bcrypt('password123'),
            'phone' => '0111111111',
            'status' => 'ACTIVE',
        ]);

        CaseModel::create([
            'case_title' => 'Downtown Robbery Case',
            'case_type' => 'Robbery',
            'case_description' => 'Robbery reported at downtown branch on 12th June',
            'case_status' => 'OPEN',
            'officer_id' => $officerUser->user_id,
        ]);
    }
}
