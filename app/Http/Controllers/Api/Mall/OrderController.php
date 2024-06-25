<?php

namespace App\Http\Controllers\Api\Mall;

use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Mall\MallOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.order')]
class OrderController extends Controller
{
    /**
     * 创建订单
     */
    #[Post('create')]
    public function create(Request $request)
    {
        DB::transaction(function () use ($request) {
            $order = MallOrder::query()
                ->create([
                    'customer_id' => $this->getCustomerId(),
                    'order_no' => MakeOrderNoEnum::MallOrder->new(),
                    'order_status' => MallOrderOrderStatusEnum::Checkout,
                    'order_money' => 0,
                    'order_fact_money' => 0,
                    'order_source' => $request->post('order_source'),
                    'name' => $request->post('name'),
                    'phone' => $request->post('phone'),
                    'province' => $request->post('province'),
                    'city' => $request->post('city'),
                    'district' => $request->post('district'),
                    'address' => $request->post('address'),
                ]);


        });

    }

    /**
     * 提交支付
     */
    public function payment()
    {

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
