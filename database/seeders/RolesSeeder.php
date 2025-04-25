<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $protectedRoles = User::PROTECTED_ROLES;

        foreach ($protectedRoles as $role) {
            Role::firstOrCreate(
                ['name' => $role],
            );
        }
    }
}
