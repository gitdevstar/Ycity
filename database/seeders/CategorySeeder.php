<?php

namespace Database\Seeders;

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
        \DB::table('categories')->delete();
        \DB::table('categories')->insert(array(
            0 => array(
                'id' => 1,
                'name' => "Fotografie",
                'icon' => "fotography.png",
            ),
            1 => array(
                'id' => 2,
                'name' => "Video",
                'icon' => "video.png",
            ),
            3 => array(
                'id' => 4,
                'name' => "Social Media",
                'icon' => "social-media.png",
            ),
            4 => array(
                'id' => 5,
                'name' => "Musik & Sound",
                'icon' => "music.png",
            ),
            5 => array(
                'id' => 6,
                'name' => "Webdesign",
                'icon' => "webdesign.png",
            ),
        ));
    }
}
