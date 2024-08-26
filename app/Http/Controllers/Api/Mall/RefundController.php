<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Mall\MallOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.refund')]
#[Middleware('auth:sanctum')]
class RefundController extends Controller
{
    #[Post('preview')]
    public function preview()
    {
        echo 123;
        exit;

    }

    /**
     * 申请退款
     */
    #[Post('create')]
    public function create(Request $request)
    {
        $mallOrder = MallOrder::query()
            ->where('customer_id', $this->getCustomerId())
            ->find($request->post('id'));

        if (in_array($mallOrder->order_status, [
            MallOrderOrderStatusEnum::Order->value,
            MallOrderOrderStatusEnum::Refund->value,
            MallOrderOrderStatusEnum::Close->value,
            MallOrderOrderStatusEnum::Checkout->value,
            MallOrderOrderStatusEnum::Cancel->value,
        ])) {
            return $this->fail('订单状态错误');
        }

        if (MallOrderOrderStatusEnum::Pay->isEq($mallOrder->order_status)) {
            // 仅退款


        } else {
            // 退货退款

        }

        dump($mallOrder->order_status);

    }

    /**
     * 退款单详情
     */
    #[Post('show')]
    public function show(Request $request)
    {

    }

}
