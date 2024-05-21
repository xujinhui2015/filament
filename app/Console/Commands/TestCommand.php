<?php

namespace App\Console\Commands;

use App\Models\Mall\MallAttr;
use App\Models\Mall\MallGoodsCategory;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'test';

    protected $description = '测试';


    public function handle(): void
    {
        $attrArray = [
            [
                1,2
            ],
            [
                9,10,11
            ],
        ];

        $result = [[]];
        foreach ($attrArray as $values) {
            $temp = [];
            foreach ($result as $combination) {
                foreach ($values as $value) {
                    $temp[] = array_merge($combination, [$value]);
                }
            }
            $result = $temp;
        }

        dump($result);



        exit;

        $recipient = User::query()->find(1);

        $recipient->notifyNow(
            Notification::make()
            ->title('Saved successfully')
            ->toDatabase()
        );
    }

}
