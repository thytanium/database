<?php

namespace Thytanium\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Seed model states.
     * 
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            ['id' => 0, 'name' => 'Inactive'],
            ['id' => 1, 'name' => 'Active'],
            ['id' => 2, 'name' => 'Banned'],
            ['id' => 3, 'name' => 'Suspended'],
            ['id' => 4, 'name' => 'Accepted'],
            ['id' => 5, 'name' => 'Published'],
            ['id' => 6, 'name' => 'Draft'],
        ]);
    }
}
