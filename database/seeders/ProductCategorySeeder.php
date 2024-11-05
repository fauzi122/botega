<?php

namespace Database\Seeders;

use App\Models\ProductCategoryModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategoryModel::query()->insert([
            ['category'=>'Kategori 1', 'descriptions'=>'-'],
            ['category'=>'Kategori 2', 'descriptions'=>'-'],
            ['category'=>'Kategori 3', 'descriptions'=>'-'],
            ['category'=>'Kategori 4', 'descriptions'=>'-'],
        ]);
    }
}
