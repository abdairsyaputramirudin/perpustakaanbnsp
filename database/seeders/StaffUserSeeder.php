<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'petugas@perpustakaan.test'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => 'password',
            ],
        );
    }
}
