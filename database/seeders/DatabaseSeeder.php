<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
          // Tambah akun admin
          User::create([
            'name' => 'Admin',
            'email' => 'admin@caffe.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif'
        ]);

        // Tambah akun kasir
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@caffe.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status' => 'aktif'
        ]);
    }
}
