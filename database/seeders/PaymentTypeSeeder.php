<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_types')->delete();
        \DB::table('payment_types')->insert(array(
            0 => array(
                'id' => 1,
                'name' => "Rechnung",
            ),
        ));
    }
}
