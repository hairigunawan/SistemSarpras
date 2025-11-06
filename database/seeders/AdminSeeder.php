<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['nama_role' => 'Admin']);
        Role::firstOrCreate(['nama_role' => 'Dosen']);
        Role::firstOrCreate(['nama_role' => 'Mahasiswa']);

        User::firstOrCreate(
            [
                'email' => 'admin@gmail.com'
            ],
            [
                'nama' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id_role,
                'avatar' => asset('favicon.ico')
            ]
        );
    }
}
