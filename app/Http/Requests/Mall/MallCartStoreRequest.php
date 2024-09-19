<?php

namespace App\Http\Requests\Mall;

use App\Models\Mall\MallGoodsSku;
use Illuminate\Foundation\Http\FormRequest;

class MallCartStoreRequest extends FormRequest
{
    protected function passedValidation(): void
    {
        $carts = $this->input('carts');
        $this->merge([
            'carts' => array_map(function ($cart) {
                $cart['goods_id'] = MallGoodsSku::query()->whereId($cart['goods_sku_id'])->value('goods_id');
                return $cart;
            }, $carts),
        ]);
    }

    public function rules(): array
    {
        return [
            'carts' => 'required|array',
            'carts.*.goods_sku_id' => 'required|integer|exists:' . MallGoodsSku::getTableName() . ',id',
            'carts.*.goods_number' => 'required|integer|min:1',
        ];
    }
}
