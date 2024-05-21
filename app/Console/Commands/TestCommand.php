<?php

namespace App\Console\Commands;

use App\Models\Mall\MallAttr;
use App\Models\Mall\MallGoodsCategory;
use App\Models\Mall\MallGoodsSku;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test';

    protected $description = '测试';


    public function handle(): void
    {
        $a = MallGoodsSku::query()
            ->find(1);

        dump($a->specName);
        dump(11);

        exit;

        $recipient = User::query()->find(1);

        $recipient->notifyNow(
            Notification::make()
                ->title('Saved successfully')
                ->toDatabase()
        );
    }

}
