<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('customer')]
#[Middleware('auth:sanctum')]
class CustomerController extends Controller
{
    /**
     * 用户资料
     */
    #[Post('show')]
    public function show(): JsonResponse
    {
        return $this->success($this->getCustomer()->only([
            'nickname', 'avatar_url', 'phone', 'balance', 'points',
        ]));
    }

    /**
     * 更新个人资料
     */
    #[Post('update')]
    public function update(Request $request): JsonResponse
    {
        $this->getCustomer()
            ->update($request->only([
                'nickname', 'avatar_url',
            ]));
        return $this->ok();
    }

    /**
     * 余额明细
     */
    #[Post('balance')]
    public function balance(): JsonResponse
    {
        return $this->success($this->getCustomer()
            ->balanceRecords()
            ->orderByDesc('id')
            ->select([
                'id', 'customer_id', 'change_explain', 'balance', 'created_at'
            ])->cursorPaginate(),
        );
    }

    /**
     * 积分明细
     */
    #[Post('points')]
    public function points(): JsonResponse
    {
        return $this->success($this->getCustomer()
            ->pointsRecords()
            ->orderByDesc('id')
            ->select([
                'id', 'customer_id', 'change_explain', 'points', 'created_at'
            ])->cursorPaginate(),
        );
    }


}
