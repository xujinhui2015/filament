<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\IsYesOrNoEnum;
use App\Http\Controllers\Controller;
use App\Models\Mall\MallGoods;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.goods')]
class GoodsController extends Controller
{
    #[Post('index')]
    public function index(): JsonResponse
    {
        return $this->success(QueryBuilder::for(MallGoods::class)
            ->allowedFilters(['goods_name'])
            ->selectRaw('id,goods_sn,goods_category_id,goods_name,main_img')
            ->where('is_sale', IsYesOrNoEnum::Yes)
            ->withMin('sku', 'price')
            ->orderByDesc('id')
            ->cursorPaginate());
    }

    #[Post('show')]
    public function show(): JsonResponse
    {
        return $this->success(QueryBuilder::for(MallGoods::class)
            ->allowedFilters([
                AllowedFilter::exact('id')->default(null)
            ])
            ->selectRaw('id,goods_sn,goods_name,subtitle,main_img,images,content,is_sale')
            ->with([
                'sku:id,goods_id,spec,price,sku_img,stock',
            ])
            ->first());
    }

}
