<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Food'
        ]);
        Category::create([
            'name' => 'Drinks'
        ]);
        Category::create([
            'name' => 'Health & Beauty'
        ]);
        Category::create([
            'name' => 'Household Cleaning'
        ]);
        Category::create([
            'name' => 'Baby Care'
        ]);
        Category::create([
            'name' => 'Plastic & Paper Products'
        ]);
        Category::create([
            'name' => 'Miscellaneous'
        ]);
    }
}
