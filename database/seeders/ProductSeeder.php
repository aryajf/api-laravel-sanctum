<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for($i=0; $i<10; $i++){
            Product::create([
                'name' => $faker->word,
                'price' => $faker->numberBetween(1000, 10000),
                'description' => $faker->sentence
            ]);
        }
    }
}
