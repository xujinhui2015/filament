<?php

namespace App\Filament\Admin\Resources\Customer\CustomerResource\Pages;

use App\Filament\Admin\Resources\Customer\CustomerResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
