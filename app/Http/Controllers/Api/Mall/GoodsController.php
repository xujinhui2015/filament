<?php

namespace App\Http\Controllers\Api\Mall;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Models\Mall\MallGoods;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
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
            ->selectRaw('id,goods_sn,goods_name,goods_category_id,goods_name,main_img')
            ->where('is_sale', true)
            ->withMin('sku', 'price')
            ->orderByDesc('id')
            ->cursorPaginate());
    }

    #[Post('show')]
    public function show(IdRequest $request): JsonResponse
    {
        return $this->success(MallGoods::query()
            ->selectRaw('id,goods_sn,goods_name,subtitle,main_img,images,content,is_sale')
            ->with([
                'sku:id,goods_id,spec,price,sku_img,stock',
                'attr' => function (HasMany $query) {
                    $query->selectRaw('id,goods_id,attr_name')
                        ->where('is_disabled', false)
                        ->orderBy('sort');
                },
                'attr.attrValue' => function (HasMany $query) {
                    $query->selectRaw('id,goods_attr_id,attr_value_name')
                        ->where('is_disabled', false)
                        ->orderBy('sort');
                }
            ])
            ->find($request->post('id')));
    }

}
