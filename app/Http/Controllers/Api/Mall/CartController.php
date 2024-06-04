<?php

namespace App\Http\Controllers\Api\Mall;

use App\Http\Controllers\Controller;
use App\Models\Mall\MallCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('mall.cart')]
#[Middleware('auth:sanctum')]
class CartController extends Controller
{
    #[Post('index')]
    public function index()
    {

    }

    #[Post('store')]
    public function store(Request $request): JsonResponse
    {
        $requestData = $request->json()->all();

        foreach ($requestData as $item) {
            $storeData = [
                'goods_id' => $item['goods_id'],
                'goods_sku_id' => $item['goods_sku_id'],
                'goods_number' => $item['goods_number'],
            ];
            if (isset($item['id'])) {
                MallCart::query()
                    ->where('customer_id', $this->getCustomerId())
                    ->update($storeData);
            } else {
                MallCart::query()
                    ->create([
                        'customer_id' => $this->getCustomerId(),
                        ... $storeData
                    ]);
            }
        }

        return $this->ok();

    }

    #[Post('destroy')]
    public function destroy(Request $request)
    {

    }

}
