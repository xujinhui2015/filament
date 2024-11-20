<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginMiniRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required',
        ];
    }
}
