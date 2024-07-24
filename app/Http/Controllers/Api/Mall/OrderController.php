<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Http\Controllers\Controller;
use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use App\Services\Customer\CustomerService;
use App\Services\Mall\MallOrderService;
use App\Services\Mall\MallService;
use App\Services\Mall\MallStockService;
use App\Support\Exceptions\ResponseException;
use App\Support\Helpers\AccuracyCalcHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Overtrue\LaravelWeChat\EasyWeChat;
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
        $detail = $request->post('detail');
        // 检查库存
        foreach ($detail as $item) {
            if (!MallStockService::checkStock($item['goods_sku_id'], $item['goods_number'])) {
                return $this->fail('库存不足');
            }
        }

        $orderId = DB::transaction(function () use ($request, $detail) {
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
     * @throws ResponseException
     */
    #[Post('payment')]
    public function payment(Request $request)
    {
        $order = MallOrder::query()
            ->where('customer_id', $this->getCustomerId())
            ->find($request->post('id'));

        if (!$order) {
            return $this->fail('订单不存在');
        }

        // 检查订单状态
        if (
            MallOrderOrderStatusEnum::Checkout->isNeq($order->order_status)
            &&  MallOrderOrderStatusEnum::Order->isNeq($order->order_status)
        ) {
            return $this->fail('订单状态异常');
        }

        // 检查库存
        foreach ($order->detail as $orderDetail) {
            if (!MallStockService::checkStock($orderDetail->goods_sku_id, $orderDetail->goods_number)) {
                return $this->fail('库存不足');
            }
        }
        $payment = $request->post('payment');
        if (MallOrderPaymentEnum::Balance->isEq($payment)) {

            Db::transaction(function () use ($order, $payment) {
                // 扣除余额
                CustomerService::setBalance(
                    $this->getCustomer(),
                    $order->order_fact_money,
                    CustomerBalanceSceneTypeEnum::MallOrder,
                    $order->id
                );
                // 完成支付
                MallOrderService::completedPay($order);
                // 扣除库存
                MallStockService::handleOrderStock($order);
            });
            return $this->success();
        } elseif ((MallOrderPaymentEnum::Wechat->isEq($payment))) {

            // 微信支付流程 1.下单时设置支付时间 2.预扣库存  2.15分钟后未支付则取消订单,并且回收库存

            return $this->fail('暂不支持微信支付');
        } else {
            return $this->fail('未知的支付方式');
        }
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
