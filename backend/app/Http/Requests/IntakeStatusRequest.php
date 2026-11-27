<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class IntakeStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return ['reference' => ['required', 'string', 'max:110']];
    }
}
