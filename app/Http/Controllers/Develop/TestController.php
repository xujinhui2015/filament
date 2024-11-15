<?php

namespace App\Http\Controllers\Develop;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\RouteAttributes\Attributes\Any;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('test')]
class TestController extends Controller
{
    #[Any('index')]
    public function index()
    {
        // https://github.com/spatie/laravel-query-builder 示例
        $list = QueryBuilder::for(Customer::class)
            // 请求传 filter[nickname]=aaaa，这里的phone表示精确过滤
            ->allowedFilters(['nickname', AllowedFilter::exact('phone')])
            // 传sort=id表示正序，传sort=-id表示倒序
            ->allowedSorts(['id', 'points'])
            ->paginate();


        // 富文本编辑器, 放到Resource中的form里面
//        QuillEditor::make('content')
//            ->required()
//            ->label('内容')
//            ->fileAttachmentsDirectory(FilePathDispose::uploadDir(FilePathDispose::DEFAULT))
//            ->columnSpanFull(),

        return $this->success($list);

    }
}
