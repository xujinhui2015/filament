<?php

namespace Database\Seeders;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerBalanceRecord;
use Database\Seeders\Mall\MallOrderSeeder;
use Illuminate\Database\Seeder;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        $this->call([
            MallOrderSeeder::class,
        ]);
    }
}
