<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class BookingAvailabilityRequest extends FormRequest
{
    public function rules(): array
    {
        return ['date' => ['nullable', 'date_format:Y-m-d']];
    }
}
