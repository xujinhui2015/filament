<?php

namespace App\Filament\Pages\Auth;

use App\Support\Helpers\FilePathHelper;
use Exception;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile;

class CustomEditProfile extends EditProfile
{
    /**
     * @throws Exception
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getAvatarUrlFormComponent(),
                        $this->getNameFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
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
