<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class VerifyPurchaseCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'transaction' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'max:40'],
        ];
    }
}
