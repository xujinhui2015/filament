<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }
}
