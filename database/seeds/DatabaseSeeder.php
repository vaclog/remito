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
        //$this->call(PermissionTableSeeder::class);
        //$this->call('PermissionTableSeeder');
        //$this->call('CustomerTableSeeder');

        $this->call('ProductTableSeeder');

    }
}
