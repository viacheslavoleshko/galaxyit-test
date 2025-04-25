<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $role = Role::where(['name' => 'admin'])->first();           

            $user = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@galaxyit.com',
            ]);

            $user->assignRole($role);
        });
    }
}
