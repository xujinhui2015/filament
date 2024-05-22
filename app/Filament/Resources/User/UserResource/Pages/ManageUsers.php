<?php

namespace App\Filament\Resources\User\UserResource\Pages;

use App\Filament\Resources\User\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * 去掉页面的标题数字
     */
    public function getHeading(): string
    {
        return '系统用户';
    }
}
