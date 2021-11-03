<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('specifications')->delete();
        \DB::table('specifications')->insert(array(
            0 => array(
                'name' => "Model",
                'icon' => "model.png",
                'cost' => 2500,
                'subcategories_fk' => 2,
                'contact' => 1,
            ),
            0 => array(
                'name' => "Drohne",
                'icon' => "drone.png",
                'cost' => 5000,
                'subcategories_fk' => 2,
                'contact' => 0,
            ),
        ));
    }
}
