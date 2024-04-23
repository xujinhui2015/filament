<?php

namespace Database\Factories\Customer;

use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

class CustomerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        $prefixes = ['130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '150', '151', '152', '153', '155', '156', '157', '158', '159', '170', '171', '173', '175', '176', '177', '178', '180', '181', '182', '183', '184', '185', '186', '187', '188', '189', '198', '199'];
        $randomPrefix = $prefixes[array_rand($prefixes)];
        $phoneNumber = $randomPrefix . str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        return [
            'nickname' =>  fake()->name(),
            'avatar' => '',
            'phone' => $phoneNumber,
            'balance' => random_int(0, 100000),
            'points' => random_int(0, 100000),
        ];
    }
}
