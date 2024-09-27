<?php

namespace App\Http\Requests\Mall\Order;

use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Models\Mall\MallGoodsSku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MallOrderCreateRequest extends FormRequest
{
    protected function passedValidation(): void
    {
        $carts = $this->input('detail');
        $this->merge([
            'detail' => array_map(function ($cart) {
                $cart['goods_id'] = MallGoodsSku::query()->whereId($cart['goods_sku_id'])->value('goods_id');
                return $cart;
            }, $carts),
        ]);
    }

    public function rules(): array
    {
        return [
            'order_source' => [
                'required',
                Rule::in(MallOrderOrderSourceEnum::values()),
            ],
            'detail' => 'required|array',
            'detail.*.goods_sku_id' => 'required|integer|exists:' . MallGoodsSku::getTableName() . ',id',
            'detail.*.goods_number' => 'required|integer|min:1',
        ];
    }
}
