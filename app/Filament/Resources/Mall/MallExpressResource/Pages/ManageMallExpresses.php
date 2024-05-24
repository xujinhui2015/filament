<?php

namespace App\Filament\Resources\Mall\MallExpressResource\Pages;

use App\Filament\Resources\Mall\MallExpressResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMallExpresses extends ManageRecords
{
    protected static string $resource = MallExpressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
