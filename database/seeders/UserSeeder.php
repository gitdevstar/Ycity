<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('users')->insert(array(
            0 => array(
                'id' => 1,
                'firstname' => "Loris",
                'lastname' => "Vonlanthen",
                'email' => "vonlanthen.loris@gmail.com",
                'password' => "$2y$10$7W6OJVDa7BQ3EnwHj2QX0Or2GM3Xwt2sM0.8gJFsfJuE4zyXRbIjm",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            )
        ));
    }
}
