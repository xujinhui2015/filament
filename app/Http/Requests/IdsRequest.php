<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ids' => 'required|array'
        ];
    }
}
