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
        //$this->call(StopsSeeder::class);
        //$this->call(VehiclesSeeder::class);
        $this->call(RoutesSeeder::class);
    }
}
