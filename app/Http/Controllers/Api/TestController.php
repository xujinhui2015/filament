<?php

namespace App\Http\Controllers\Api;

use App\Casts\MoneyCast;
use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\RouteAttributes\Attributes\Any;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('test')]
class TestController extends Controller
{
    #[Any('/')]
    public function index()
    {
        // https://github.com/spatie/laravel-query-builder 示例
        $list = QueryBuilder::for(Customer::class)
            ->allowedFilters(['nickname', AllowedFilter::exact('phone')]) // 请求传 filter[nickname]=aaaa，这里的phone表示精确过滤
            ->allowedSorts(['id', 'points']) // 传sort=id表示正序，传sort=-id表示倒序
            ->jsonPaginate();

        $a = Customer::query()->find(1);
        dump($a->balance);
        exit;

        return $this->success($list);

    }
}
