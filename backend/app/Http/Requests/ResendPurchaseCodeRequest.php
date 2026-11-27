<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ResendPurchaseCodeRequest extends FormRequest
{
    public function rules(): array
    {
        return ['transaction' => ['required', 'string', 'max:120']];
    }
}
