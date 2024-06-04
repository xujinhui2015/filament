<?php

namespace App\Http\Controllers;

use App\Models\Customer\Customer;
use Jiannei\Response\Laravel\Support\Traits\JsonResponseTrait;

abstract class Controller
{
    use JsonResponseTrait;

    protected function getCustomer(): Customer
    {
        /** @var Customer */
        return auth()->user();
    }

    protected function getCustomerId(): int|string|null
    {
        return auth()->id();
    }
}
