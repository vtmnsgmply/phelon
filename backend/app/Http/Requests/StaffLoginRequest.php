<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StaffLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:254'],
            'password' => ['required', 'string', 'max:72'],
        ];
    }
}
