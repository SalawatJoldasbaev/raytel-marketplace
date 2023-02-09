<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Store;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Settings::create([
            'description'=> 'description',
            'title'=> 'title',
            'price'=> 22000,
            'card_number'=> '0000 0000 0000 0000',
            'card_holder'=> 'CARD HOLDER',
            'block_text'=> 'block text',
            'unblock_text'=> 'unblock text',
            'phone'=> '+998953558899',
            'end_text'=> 'end text',
        ]);
        Store::factory()->has(Product::factory()->count(33))->count(100)->create();
        $this->call([
            EmployeeSeeder::class,
//            StoreSeeder::class,
//            ProductSeeder::class,
        ]);
    }
}
