<?php

namespace App\Http\Controllers\Api\Mall;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.refund')]
#[Middleware('auth:sanctum')]
class RefundController extends Controller
{
    /**
     * 申请退款
     */
    #[Post('create')]
    public function create(Request $request)
    {

    }

    /**
     * 退款单详情
     */
    #[Post('show')]
    public function show(Request $request)
    {

    }

}
