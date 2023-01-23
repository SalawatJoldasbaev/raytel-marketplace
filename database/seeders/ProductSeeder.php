<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $description = [
            'the description',
            null,
            'the product description',
            'the product',
            'opisane',
        ];

        for ($i = 1; $i <= 150; $i++) {
            Product::create([
                'store_id' => rand(1, 3),
                'name' => 'Product ' . $i,
                'image' => "http://127.0.0.1:8000/api/files/Grb3iMBZxCm5Xu05j2TVCsPAzPk6RxZTf8l2lshQ.jpg",
                "description" => $description[rand(0, 4)],
            ]);
        }
    }
}
