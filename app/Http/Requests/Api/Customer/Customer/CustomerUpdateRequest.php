<?php

namespace App\Http\Requests\Api\Customer\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nickname' => 'max:32',
            'avatar_url' => 'max:255',
        ];
    }
}
