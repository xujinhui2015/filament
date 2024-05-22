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

        return [
            'nickname' =>  fake()->name(),
            'avatar_url' => '',
            'phone' => fake_phone(),
            'balance' => random_int(0, 1000),
            'points' => random_int(0, 100000),
        ];
    }
}
