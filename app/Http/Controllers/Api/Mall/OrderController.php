<?php

namespace App\Http\Controllers\Api\Mall;

use App\Http\Controllers\Controller;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.order')]
class OrderController extends Controller
{
    /**
     * 创建订单
     */
    #[Post('create')]
    public function create()
    {

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
