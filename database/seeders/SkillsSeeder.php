<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('skills')->delete();
        \DB::table('skills')->insert(array(
            0 => array(
                'id' => 1,
                'name' => "Slowmotion",
                'categories_fk' => 2,
            ),
            1 => array(
                'id' => 2,
                'name' => "Zeitraffer",
                'categories_fk' => 2,
            ),
            2 => array(
                'id' => 3,
                'name' => "Photoshop",
                'categories_fk' => 1,
            ),
            3 => array(
                'id' => 4,
                'name' => "Video",
                'categories_fk' => 2,
            ),
            4 => array(
                'id' => 5,
                'name' => "Foto",
                'categories_fk' => 1,
            ),
            5 => array(
                'id' => 6,
                'name' => "Drohnenpilot",
                'categories_fk' => 2,
            ),
        ));
    }
}
