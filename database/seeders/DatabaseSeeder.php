<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use illuminate\Support\facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
          User::create([
            'name' => 'Admin',
            'email' => 'admin@caffe.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif'
        ]);

        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@caffe.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status' => 'aktif'
        ]);

        // Tambah akun kasir
        User::create([
            'name' => 'Owner',
            'email' => 'owner@caffe.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'aktif'
        ]);

         // Tambah akun kasir
        // User::create([
        //     'name' => 'Chef',
        //     'email' => 'chef@caffe.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'chef',
        //     'status' => 'aktif'
        // ]);


        // $this->call([
        //     KategoriSeeder::class,
        // //     ProdukSeeder::class
        // ]);
    }
     
}
