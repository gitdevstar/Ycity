<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('status')->delete();
        \DB::table('status')->insert(array(
            0 => array(
                'id' => 1,
                'name' => "offen",
            ),
            1 => array(
                'id' => 2,
                'name' => "in Bearbeitung",
            ),
            2 => array(
                'id' => 3,
                'name' => "abgeschlossen",
            ),
            3 => array(
                'id' => 4,
                'name' => "abgelaufen",
            ),
        ));
    }
}
