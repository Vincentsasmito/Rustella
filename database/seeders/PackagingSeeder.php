<?php

namespace Database\Seeders;

use App\Models\Packaging;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackagingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Packaging::create([
            'name'              => 'Bouquet',
            'price'              => 10000,
        ]);
        Packaging::create([
            'name'              => 'Bloom Box',
            'price'             => 20000,
        ]);
        Packaging::create([
            'name'              => 'Flower Bag',
            'price'             => 15000,
        ]);
        Packaging::create([
            'name'              => 'Flower Basket',
            'price'             => 7500,
        ]);
    }
}
