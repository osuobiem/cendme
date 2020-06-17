<?php

use App\AgentLevel;
use Illuminate\Database\Seeder;

class AgentLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AgentLevel::create([
            'name' => 'Level 1',
            'commision' => 2,
            'max_orders' => '200'
        ]);

        AgentLevel::create([
            'name' => 'Level 2',
            'commision' => 2.3,
            'max_orders' => '400'
        ]);

        AgentLevel::create([
            'name' => 'Level 3',
            'commision' => 2.5,
            'max_orders' => '600'
        ]);

        AgentLevel::create([
            'name' => 'Level 4',
            'commision' => 2.7,
            'max_orders' => '800'
        ]);

        AgentLevel::create([
            'name' => 'Level 5',
            'commision' => 3,
            'max_orders' => '1000+'
        ]);
    }
}
