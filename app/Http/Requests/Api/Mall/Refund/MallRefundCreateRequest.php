<?php

namespace App\Http\Requests\Api\Mall\Refund;

use App\Models\Mall\MallOrderDetail;
use Illuminate\Foundation\Http\FormRequest;

class MallRefundCreateRequest extends FormRequest
{
    protected function passedValidation(): void
    {
        $carts = $this->input('detail');
        $this->merge([
            'detail' => array_map(function ($cart) {
                $detail = MallOrderDetail::query()
                    ->whereId($cart['order_detail_id'])
                    ->selectRaw('goods_id,goods_sku_id')
                    ->first();
                $cart['goods_id'] = $detail->goods_id;
                $cart['goods_sku_id'] = $detail->goods_sku_id;
                return $cart;
            }, $carts),
        ]);
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'refund_reason' => 'nullable|max:500',
            'buyer_message' => 'nullable|max:500',
            'buyer_images' => 'nullable|array',
            'detail' => 'required|array',
            'detail.*.order_detail_id' => 'required|integer',
            'detail.*.refund_number' => 'required|integer|min:1',
        ];
    }
}
