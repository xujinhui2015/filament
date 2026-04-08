<?php

namespace App\Http\Controllers\Develop;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TestController extends Controller
{
    public function index()
    {
        // https://github.com/spatie/laravel-query-builder 示例
        $list = QueryBuilder::for(Customer::class)
            // 请求传 filter[nickname]=aaaa，这里的phone表示精确过滤
            ->allowedFilters(['nickname', AllowedFilter::exact('phone')])
            // 传sort=id表示正序，传sort=-id表示倒序
            ->allowedSorts(['id', 'points'])
            ->paginate();

        return $this->success($list);

    }
}
