<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SubcategorySeeder::class);
        $this->call(SpecificationSeeder::class);
        $this->call(ComplexitySeeder::class);
        $this->call(JobSkillsSeeder::class);
        $this->call(SkillsSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(JobSeeder::class);
    }
}
