<?php

namespace App\Console\Commands\Tools;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerBalanceRecord;
use App\Models\Customer\CustomerPointsRecord;
use App\Models\Customer\CustomerWechat;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class ClearTableCommand extends Command  implements Isolatable
{
    protected $signature = 'clear-table';

    protected $description = '清理表数据';

    public function handle(): void
    {
        if (! $this->confirm('确认要清理表数据吗?')) {
            $this->info('已取消');

            return;
        }

        if (! $this->confirm('再次确认要清理表数据吗?')) {
            $this->info('已取消');

            return;
        }

        // 用户相关信息
        Customer::query()->truncate();
        CustomerPointsRecord::query()->truncate();
        CustomerBalanceRecord::query()->truncate();
        CustomerWechat::query()->truncate();

        $this->info('操作成功!');

    }
}
