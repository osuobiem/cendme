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
        DB::table('credentials')->insert(
            [
                'key' => 'paystack_secret_key',
                'value' => 'sk_test_0df67e05bce39bc55365f9334a815c48da2a4483',
            ]
        );

        DB::table('credentials')->insert(
            [
                'key' => 'paystack_public_key',
                'value' => 'pk_test_1bc8133fe2c65d13ec694d9ccba440f04c9e0689'
            ]
        );

        DB::table('credentials')->insert(
            [
                'key' => 'flutter_secret_key',
                'value' => 'FLWSECK_TEST-75df3c1099c66f773bcef12b932172ea-X',
            ]
        );

        DB::table('credentials')->insert(
            [
                'key' => 'flutter_enc_key',
                'value' => 'FLWSECK_TESTaf7a52e83f04',
            ]
        );

        DB::table('credentials')->insert(
            [
                'key' => 'flutter_public_key',
                'value' => 'FLWPUBK_TEST-42e14d8c9512f5596b6a7b50d0d61bd4-X'
            ]
        );

        DB::table('credentials')->insert(
            [
                'key' => 'google_api_key',
                'value' => 'AIzaSyCcgMVuEhhq6Yiw7Lb34Z6Cz79CN4uTHKY'
            ]
        );
    }
}
