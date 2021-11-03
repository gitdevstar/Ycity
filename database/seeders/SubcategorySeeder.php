<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subcategories')->delete();
        \DB::table('subcategories')->insert(array(
            0 => array(
                'name' => "IMAGESPOT",
                'icon' => "imagespot.png",
                'cost' => 5000,
                'categories_fk' => 2,
            ),
            1 => array(
                'name' => "B2B LinkedIn",
                'icon' => "linkedin.png",
                'cost' => 1000,
                'categories_fk' => 2,
            ),
            2 => array(
                'name' => "Immobilie",
                'icon' => "immobilie.png",
                'cost' => 1000,
                'categories_fk' => 2,
            ),
            3 => array(
                'name' => "Event",
                'icon' => "event.png",
                'cost' => 1000,
                'categories_fk' => 2,
            ),
            4 => array(
                'name' => "Ad",
                'icon' => "ad.png",
                'cost' => 1000,
                'categories_fk' => 2,
            ),
            5 => array(
                'name' => "Success Story",
                'icon' => "success-story.png",
                'cost' => 3000,
                'categories_fk' => 2,
            ),
            6 => array(
                'name' => "Ad",
                'icon' => "ad.png",
                'cost' => 1000,
                'categories_fk' => 4,
            ),
            7 => array(
                'name' => "Story",
                'icon' => "story.png",
                'cost' => 1000,
                'categories_fk' => 4,
            ),
            8 => array(
                'name' => "Content",
                'icon' => "content.png",
                'cost' => 1000,
                'categories_fk' => 4,
            ),
            9 => array(
                'name' => "Post",
                'icon' => "post.png",
                'cost' => 1000,
                'categories_fk' => 4,
            ),
            10 => array(
                'name' => "Logo",
                'icon' => "logo.png",
                'cost' => 2000,
                'categories_fk' => 4,
            ),
            11 => array(
                'name' => "Lyrics",
                'icon' => "lyrics.png",
                'cost' => 2000,
                'categories_fk' => 5,
            ),
            12 => array(
                'name' => "Beat",
                'icon' => "beat.png",
                'cost' => 2000,
                'categories_fk' => 5,
            ),
            13 => array(
                'name' => "Song",
                'icon' => "song.png",
                'cost' => 4000,
                'categories_fk' => 5,
            ),
            14 => array(
                'name' => "Mockup",
                'icon' => "mockup.png",
                'cost' => 2000,
                'categories_fk' => 6,
            ),
            15 => array(
                'name' => "Wireframe",
                'icon' => "wireframe.png",
                'cost' => 2000,
                'categories_fk' => 6,
            ),
            16 => array(
                'name' => "Code",
                'icon' => "code.png",
                'cost' => 2000,
                'categories_fk' => 6,
            ),
        ));
    }
}
