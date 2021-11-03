<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ComplexitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('complexities')->delete();
        \DB::table('complexities')->insert(array(
            0 => array(
                'id' => 1,
                'name' => "einfach",
            ),
            1 => array(
                'id' => 2,
                'name' => "mittel",
            ),
            2 => array(
                'id' => 3,
                'name' => "schwierig",
            ),
        ));
    }
}
