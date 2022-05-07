<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Artisan;

class ModuleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call("module:install");
    }
}
