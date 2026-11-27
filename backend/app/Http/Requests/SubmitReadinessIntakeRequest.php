<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SubmitReadinessIntakeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:110'],
            'answers' => ['required', 'json', 'max:85000'],
            'files' => ['sometimes', 'array', 'max:4'],
            'files.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'extensions:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
