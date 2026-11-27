<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class RunMigrationsRequest extends FormRequest
{
    public function rules(): array
    {
        return ['password' => ['bail', 'required', 'string', 'max:72', 'current_password:web']];
    }
}
