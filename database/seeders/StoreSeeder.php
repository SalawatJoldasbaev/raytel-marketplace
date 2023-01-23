<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
                'image' => "http://127.0.0.1:8000/api/files/Grb3iMBZxCm5Xu05j2TVCsPAzPk6RxZTf8l2lshQ.jpg",
                'name' => 'Store 1',
                'phone' => '+99890701931',
                'description' => 'Description'
            ],
            [
                'image' => "http://127.0.0.1:8000/api/files/Grb3iMBZxCm5Xu05j2TVCsPAzPk6RxZTf8l2lshQ.jpg",
                'name' => 'Store 2',
                'phone' => '+998953558899',
                'description' => null
            ],
            [
                'image' => "http://127.0.0.1:8000/api/files/Grb3iMBZxCm5Xu05j2TVCsPAzPk6RxZTf8l2lshQ.jpg",
                'name' => 'Store 4',
                'phone' => '+998906622939',
                'description' => '2939'
            ],
        ];
        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
