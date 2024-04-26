<?php

namespace App\Filament\Resources\Customer\CustomerResource\Pages;

use App\Filament\Resources\Customer\CustomerResource;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
