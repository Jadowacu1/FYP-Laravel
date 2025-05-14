<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\seeders\CampusesSeeder;
use Database\seeders\FacultiesSeeder;

class DatabaseSeeder extends Seeder {
    /**
    * Seed the application's database.
    */

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }
    

}