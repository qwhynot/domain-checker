<?php

namespace App\Http\Requests;

use App\Enums\CheckMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('domain'));
    }

    public function rules(): array
    {
        return [
            'url' => ['required', 'url', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'check_interval' => ['required', 'integer', 'min:1', 'max:1440'],
            'check_timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'check_method' => ['required', new Enum(CheckMethod::class)],
            'is_active' => ['boolean'],
        ];
    }
}
