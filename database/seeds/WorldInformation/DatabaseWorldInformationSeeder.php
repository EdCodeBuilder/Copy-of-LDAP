<?php

use Illuminate\Database\Seeder;

class DatabaseWorldInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(CitiesPart2TableSeeder::class);
        $this->call(CitiesPart3TableSeeder::class);
        $this->call(CitiesPart4TableSeeder::class);
        $this->call(CitiesPart5TableSeeder::class);
        $this->call(CitiesPart6TableSeeder::class);
        $this->call(CitiesPart7TableSeeder::class);
        $this->call(CitiesPart8TableSeeder::class);
    }
}