<?php

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
        // $this->call(UsersTableSeeder::class);
        factory(App\Modules\CitizenPortal\src\Models\Users_assistance::class,50)->create();
        factory(App\Modules\CitizenPortal\src\Models\ActivityAsisstance::class,30)->create();

    }
}
