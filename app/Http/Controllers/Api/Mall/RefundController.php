<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mall\Refund\MallRefundCreateRequest;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderDetail;
use App\Models\Mall\MallOrderRefund;
use App\Support\Helpers\AccuracyCalcHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.refund')]
#[Middleware('auth:sanctum')]
class RefundController extends Controller
{

    #[Post('index')]
    public function index(): JsonResponse
    {
        return $this->success(QueryBuilder::for(MallOrderRefund::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('refund_status', function ($query, $filterRefundStatus) {
                    // 0退款中1退款成功2退款关闭
                    if ($filterRefundStatus == 0) {
                        $query->whereIn('refund_status', [
                            MallOrderRefundRefundStatusEnum::Applied,
                            MallOrderRefundRefundStatusEnum::Approved,
                            MallOrderRefundRefundStatusEnum::BuyerReturned,
                            MallOrderRefundRefundStatusEnum::ReturnReceived,
                            MallOrderRefundRefundStatusEnum::Confirmed,
                        ]);
                    } elseif ($filterRefundStatus == 1) {
                        $query->where('refund_status', MallOrderRefundRefundStatusEnum::Successful);
                    } elseif ($filterRefundStatus == 2) {
                        $query->where('refund_status', MallOrderRefundRefundStatusEnum::Closed);
                    }
                })
            ])
            ->whereHas('order', function ($query) {
                $query->where('customer_id', $this->getCustomerId());
            })
            ->with([
                'detail:id,order_refund_id,order_detail_id,goods_id,goods_sku_id,refund_number',
                'detail.orderDetail:id,order_id,goods_id,goods_sku_id,goods_name,goods_spec,goods_image,goods_price,goods_number',
            ])
            ->orderByDesc('id')
            ->selectRaw('id,order_id,refund_order_no,refund_type,refund_status,refund_money,phone,refund_reason,buyer_message,seller_message,created_at')
            ->cursorPaginate());
    }

    /**
     * 申请退款
     */
    #[Post('create')]
    public function create(MallRefundCreateRequest $request)
    {
        $mallOrder = MallOrder::query()
            ->where('customer_id', $this->getCustomerId())
            ->find($request->post('id'));

        if (!$mallOrder) {
            return $this->fail('订单不存在');
        }

        if (in_array($mallOrder->order_status, [
            MallOrderOrderStatusEnum::Order->value,
            MallOrderOrderStatusEnum::Refund->value,
            MallOrderOrderStatusEnum::Close->value,
            MallOrderOrderStatusEnum::Checkout->value,
            MallOrderOrderStatusEnum::Cancel->value,
        ])) {
            return $this->fail('订单状态错误');
        }
        $detail = collect($request->post('detail'));

        $mallOrder->load([
            'detail' => function (HasMany $query) use ($detail) {
                $query->whereIn('goods_sku_id', $detail->pluck('goods_sku_id'));
            }
        ]);

        // 获取退款总金额
        $refundMoney = $mallOrder->detail->sum(function (MallOrderDetail $orderDetail) use ($detail, &$orderDetails) {
            return AccuracyCalcHelper::begin($orderDetail->goods_price)
                ->mul($detail->where('goods_sku_id', $orderDetail->goods_sku_id)->value('refund_number'))
                ->result();
        });

        DB::transaction(function () use ($mallOrder, $refundMoney, $request, $detail) {
            /** @var MallOrderRefund $refund */
            $refund = MallOrderRefund::query()->create([
                'order_id' => $mallOrder->id,
                'refund_order_no' => MakeOrderNoEnum::MallRefundOrder->new(),
                'refund_type' =>
                    $mallOrder->order_status->isEq(MallOrderOrderStatusEnum::Pay)
                        ? MallOrderRefundRefundTypeEnum::Only : MallOrderRefundRefundTypeEnum::Return,
                'refund_status' => MallOrderRefundRefundStatusEnum::Applied,
                'refund_money' => $refundMoney,
                'phone' => $request->post('phone'),
                'refund_reason' => $request->post('refund_reason'),
                'buyer_message' => $request->post('buyer_message'),
                'buyer_images' => $request->post('buyer_images'),
            ]);

            $refund->detail()->createMany($detail);
            // 设置主订单状态
            $mallOrder->update([
                'order_status' => MallOrderOrderStatusEnum::Refund,
            ]);
        });

        return $this->ok();
    }

    /**
     * 退货退款-买家发货
     */
    #[Post('delivery')]
    public function delivery(Request $request)
    {
        /** @var MallOrderRefund $orderRefund */
        $orderRefund = MallOrderRefund::query()
            ->whereHas('order', function (Builder $query) {
                $query->where('customer_id', $this->getCustomerId());
            })
            ->find($request->post('id'));

        if (!$orderRefund) {
            return $this->fail('退款订单不存在');
        }

        if (
            $orderRefund->refund_status->isNeq(MallOrderRefundRefundStatusEnum::Approved)
            || $orderRefund->refund_type->isNeq(MallOrderRefundRefundTypeEnum::Return)
        ) {
            return $this->fail('退款订单状态不正确');
        }

        DB::transaction(function () use ($orderRefund, $request) {
            $orderRefund->logistics()->updateOrCreate([], $request->only([
                'logistics_company_name', 'logistics_no'
            ]));
            $orderRefund->update([
                'refund_status' => MallOrderRefundRefundStatusEnum::BuyerReturned,
            ]);
        });

        return $this->ok();
    }

}
