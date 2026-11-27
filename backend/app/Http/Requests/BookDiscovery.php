<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BookDiscovery extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'start' => ['required', 'date_format:Y-m-d\TH:i:s\Z'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'phone' => ['required', 'string', 'regex:/\A(?:[+() .-]*\d){7,}[+() .-]*\z/', 'max:32'],
            'state' => ['required', Rule::in(explode(' ', 'AL AK AZ AR CA CO CT DE DC FL GA HI ID IL IN IA KS KY LA ME MD MA MI MN MS MO MT NE NV NH NJ NM NY NC ND OH OK OR PA RI SC SD TN TX UT VT VA WA WV WI WY AS GU MP PR VI'))],
            'timezone' => ['required', 'timezone', 'max:80'],
            'concern' => ['required', 'string', 'max:300'],
            'interest' => ['nullable', 'string', 'max:150'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('state'))) {
            $this->merge(['state' => strtoupper($this->input('state'))]);
        }
    }
}
