<?php

namespace App\Http\Requests\Mall;

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
            'name' => 'required|max:32',
            'phone' => 'required|max:32',
            'province' => 'required|max:32',
            'city' => 'required|max:32',
            'district' => 'required|max:32',
            'address' => 'required|max:255',
            'detail' => 'required|array',
            'detail.*.goods_sku_id' => 'required|integer|exists:' . MallGoodsSku::getTableName() . ',id',
            'detail.*.goods_number' => 'required|integer|min:1',
        ];
    }
}
