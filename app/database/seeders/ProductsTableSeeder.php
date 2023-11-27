<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Pastel de Queijo',
            'price_cents' => 1999,
        ]);

        Product::create([
            'name' => 'Pastel de Frango',
            'price_cents' => 2999,
        ]);
    }
}
