<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = DB::table('kategori')->inRandomOrder()->select('id')->first();

        return [
            // 'id' => "PRD" .sprintf("%08d", fake()->unique()->numberBetween(1, 99999999)),
            // 'kategori_id' => fake()->randomElement(Kategori::select('id')->get()),
            // 'nama_produk' => fake()->randomElement(['Nasi Goreng', 'Ramen', 'Kiss Cake', 'Mie Goreng', 'Ayam Goreng']),
            // 'harga' => fake()->numberBetween(1000, 10000),
            // 'foto' => fake()->imageUrl(640, 480, 'food', true, 'produk'), 
            // 'stok' => fake()->numberBetween(1, 100),

        ];
    }
}
