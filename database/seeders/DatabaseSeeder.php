<?php

namespace Database\Seeders;

use Database\Seeders\Mall\MallDemoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MallDemoSeeder::class,
        ]);
    }
}
