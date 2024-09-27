<?php

namespace App\Http\Requests\Mall\Order;

use Illuminate\Foundation\Http\FormRequest;

class MallOrderCalcRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'name' => 'required|max:32',
            'phone' => 'required|max:32',
            'province' => 'required|max:32',
            'city' => 'required|max:32',
            'district' => 'required|max:32',
            'address' => 'required|max:255',
            'buyer_remark' => 'nullable|max:255',
        ];
    }
}
