<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\Kategori;
use Illuminate\Support\Facades\File;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = File::get('database/data/cashpos.json');
        $data = json_decode($file);
        foreach ($data as $obj) {
            Kategori::create([
                'nama_kategori' => $obj->nama_kategori,
            ]);
        }
        // Kategori::factory()->count(4)->create();
    }
}
