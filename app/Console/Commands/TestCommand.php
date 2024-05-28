<?php

namespace App\Console\Commands;

use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test';

    protected $description = '测试';


    public function handle(): void
    {
        $a = 'page_CustomerResource';
        $b = 'page_UserResource';

        $aa = User::query()->find(3)->can($a);
        $bb = User::query()->find(3)->can($b);
        dump($aa);
        dump($bb);
        exit;
        $order = MallOrder::query()->find(30);
        dump($order->delivery_time);

        exit;

        $recipient = User::query()->find(1);

        $recipient->notifyNow(
            Notification::make()
                ->title('Saved successfully')
                ->toDatabase()
        );
    }

}
