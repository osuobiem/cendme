<?php

use App\ShopperLevel;
use Illuminate\Database\Seeder;

class ShopperLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShopperLevel::create([
            'name' => 'Level 1',
            'commision' => 2,
            'max_orders' => '200'
        ]);

        ShopperLevel::create([
            'name' => 'Level 2',
            'commision' => 2.3,
            'max_orders' => '400'
        ]);

        ShopperLevel::create([
            'name' => 'Level 3',
            'commision' => 2.5,
            'max_orders' => '600'
        ]);

        ShopperLevel::create([
            'name' => 'Level 4',
            'commision' => 2.7,
            'max_orders' => '800'
        ]);

        ShopperLevel::create([
            'name' => 'Level 5',
            'commision' => 3,
            'max_orders' => '1000+'
        ]);
    }
}
