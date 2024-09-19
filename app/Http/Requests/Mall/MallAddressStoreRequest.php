<?php

namespace App\Http\Requests\Mall;

use Illuminate\Foundation\Http\FormRequest;

class MallAddressStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'integer',
            'name' => 'required|max:32',
            'phone' => 'required|max:32',
            'province' => 'required|max:32',
            'city' => 'required|max:32',
            'district' => 'required|max:32',
            'address' => 'required|max:255',
        ];
    }
}
