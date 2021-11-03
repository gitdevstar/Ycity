<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('clients')->delete();
        \DB::table('clients')->insert(array(
            0 => array(
                'users_fk' => 1,
                'name' => "Y-City",
                'description' => "Das ist eine gute Firma.",
                'street' => "Adlerwiese 7",
                'plz' => 8862,
                'email' => "loris@y-city.ch",
                'website' => "https://www.y-city.ch",
                'telephone' => "762461772",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ),
            1 => array(
                'users_fk' => 1,
                'name' => "NK Media",
                'description' => "NK Media ist jung und wild.",
                'street' => "Adlerwiese 7",
                'plz' => 8862,
                'email' => "loris@nk-media.ch",
                'website' => "https://www.y-city.ch",
                'telephone' => "762461772",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            )
        ));
    }
}
