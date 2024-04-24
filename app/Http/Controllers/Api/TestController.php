<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
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
        /** @var LengthAwarePaginator $list */
        $list = QueryBuilder::for(Customer::class)
            // 请求传 filter[nickname]=aaaa，这里的phone表示精确过滤
            ->allowedFilters(['nickname', AllowedFilter::exact('phone')])
            // 传sort=id表示正序，传sort=-id表示倒序
            ->allowedSorts(['id', 'points'])
            ->paginate();

        return $this->success($list);

    }
}
