<?php

namespace App\Filament\Admin\Pages\Auth;

use App\Support\Helpers\FilePathHelper;
use Filament\Auth\Pages\EditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class CustomEditProfile extends EditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarUrlFormComponent(),
                $this->getNameFormComponent(),
                $this->getPhoneFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getAvatarUrlFormComponent(): FileUpload
    {
        return FileUpload::make('avatar_url')
            ->avatar()
            ->imageEditor()
            ->getUploadedFileNameForStorageUsing(FilePathHelper::uploadUsing(FilePathHelper::AVATAR))
            ->label('头像');
    }

    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label('手机号')
            ->maxLength(32)
            ->disabled()
            ->helperText('修改手机号请联系管理员');
    }
}
