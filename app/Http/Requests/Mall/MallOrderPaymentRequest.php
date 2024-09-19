<?php

namespace App\Http\Requests\Mall;

use App\Enums\Mall\MallOrderPaymentEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MallOrderPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'payment' => [
                'required',
                Rule::in(MallOrderPaymentEnum::values()),
            ],
        ];
    }
}
