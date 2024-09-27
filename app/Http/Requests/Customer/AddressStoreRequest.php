<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
