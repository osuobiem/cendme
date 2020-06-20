<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Credentials extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('credentials')->insert(
        //     [
        //         'key' => 'paystack_secret_key',
        //         'value' => 'sk_test_cfeddb7779ff834c310e8150fad059a4897953ee',
        //     ]
        // );

        // DB::table('credentials')->insert(
        //     [
        //         'key' => 'paystack_public_key',
        //         'value' => 'pk_test_86f90cf4ce59fc889b74d6e0cfe8ddcdaaa4c592'
        //     ]
        // );

        DB::table('credentials')->insert(
            [
                'key' => 'google_api_key',
                'value' => 'AIzaSyCcgMVuEhhq6Yiw7Lb34Z6Cz79CN4uTHKY'
            ]
        );
    }
}
