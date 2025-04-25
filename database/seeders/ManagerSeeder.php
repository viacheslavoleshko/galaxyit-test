<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            User::$bypassRoleAssignment = true;
            $role = Role::where(['name' => 'manager'])->first();           

            $user = User::factory()->create([
                'email' => 'manager@galaxyit.com',
            ]);

            $user->assignRole($role);

            User::$bypassRoleAssignment = false;
        });
    }
}
