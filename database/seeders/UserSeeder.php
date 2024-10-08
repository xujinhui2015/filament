<?php

namespace Database\Seeders;

use App\Models\Customer\Customer;
use Illuminate\Database\Seeder;
use Random\RandomException;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        // 生成用户信息
        Customer::factory()
            ->count(50)
            ->create();

        // 生成用户余额和积分记录
        Customer::all()->each(function (Customer $customer) {
            // 生成余额记录
            $balance = $customer->balance;
            $selfBalance = 0;

            $balances = [];
            while (bccomp($balance, $selfBalance, 2) == 1) {
                $selfBalance = bcdiv(random_int(0, 5000), 100, 2);
                $balance = bcsub($balance, $selfBalance, 2);
                $balances[] = $selfBalance;
            }
            if (bccomp($balance, 0, 2) == 1) {
                $balances[] = $balance;
            }

            if (isset($balances)) {
                $customer->balanceRecords()->createMany(array_map(function ($balance) {
                    return [
                        'change_explain' => '自动生成',
                        'scene_type' => 0,
                        'balance' => $balance,
                        'relation_id' => 0,
                        'remark' => '',
                    ];
                }, $balances));
            }

            // 生成积分记录
            $points = $customer->points;
            $selfPoints = 0;

            $pointsMany = [];
            while ($points > $selfPoints) {
                $selfPoints = random_int(0, 3000);
                $points = $points - $selfPoints;
                $pointsMany[] = $selfPoints;
            }
            if ($points > 0) {
                $pointsMany[] = $points;
            }
            if (isset($pointsMany)) {
                $customer->pointsRecords()->createMany(array_map(function ($points) {
                    return [
                        'change_explain' => '自动生成',
                        'scene_type' => 0,
                        'points' => $points,
                        'relation_id' => 0,
                        'remark' => '',
                    ];
                }, $pointsMany));
            }
        });
    }
}
