<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Cendme Superadmin',
            'username' => 'superadmin',
            'password' => Hash::make('123456'),
            'type' => 'superadmin'
        ]);
    }
}
