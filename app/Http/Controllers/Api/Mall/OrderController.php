<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use App\Support\Helpers\AccuracyCalcHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.order')]
#[Middleware('auth:sanctum')]
class OrderController extends Controller
{
    /**
     * 创建订单
     */
    #[Post('create')]
    public function create(Request $request): JsonResponse
    {
        $orderId = DB::transaction(function () use ($request) {
            $order = MallOrder::query()
                ->create([
                    'customer_id' => $this->getCustomerId(),
                    'order_no' => MakeOrderNoEnum::MallOrder->new(),
                    'order_status' => MallOrderOrderStatusEnum::Checkout,
                    'order_money' => 0,
                    'order_fact_money' => 0,
                    ... $request->only([
                        'order_source', 'name', 'phone',
                        'province', 'city', 'district', 'address'
                    ]),
                ]);
            $detail = $request->post('detail');

            $goodsSkus = MallGoodsSku::query()
                ->whereIn('id', array_column($detail, 'goods_sku_id'))
                ->with('goods')
                ->get();

            $orderFactMoney = AccuracyCalcHelper::begin(0);

            $order->detail()->createMany(array_map(function ($row) use ($goodsSkus, $orderFactMoney) {

                $goodsSku = $goodsSkus->find($row['goods_sku_id']);

                $detailGoodsPrice = AccuracyCalcHelper::begin($goodsSku->price)->mul($row['goods_number'])->result();

                $orderFactMoney->add($detailGoodsPrice);

                return [
                    'goods_id' => $row['goods_id'],
                    'goods_sku_id' => $row['goods_sku_id'],
                    'goods_name' => $goodsSku->goods->goods_name,
                    'goods_spec' => $goodsSku->getAttribute('spec_text'),
                    'goods_image' => $goodsSku->goods->main_img,
                    'goods_price' => $detailGoodsPrice,
                    'goods_number' => $row['goods_number'],
                ];
            }, $detail));

            $order->update([
                'order_money' => $orderFactMoney->result(),
                'order_fact_money' => $orderFactMoney->result(),
            ]);

            return $order->id;
        });

        return $this->success([
            'id' => $orderId,
        ]);
    }

    /**
     * 提交支付
     */
    #[Post('payment')]
    public function payment(Request $request)
    {
        $order = MallOrder::query()
            ->find($request->post('id'));
        dump($order->toArray());
        exit;

    }

    /**
     * 订单列表
     */
    #[Post('index')]
    public function index()
    {

    }

    /**
     * 订单详情
     */
    #[Post('show')]
    public function show()
    {

    }


}
