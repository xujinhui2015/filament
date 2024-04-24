<?php

namespace App\Console\Commands\Tools;

use App\Models\Customer\Customer;
use App\Models\Customer\CustomerBalances;
use App\Models\Customer\CustomerPoints;
use App\Models\Customer\CustomerWechat;
use Illuminate\Console\Command;

class ClearTableCommand extends Command
{
    protected $signature = 'clear-table';

    protected $description = '清理表数据';

    public function handle()
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
        CustomerPoints::query()->truncate();
        CustomerBalances::query()->truncate();
        CustomerWechat::query()->truncate();

        $this->info('操作成功!');

    }
}
