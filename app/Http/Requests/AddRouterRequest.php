<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddRouterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'host' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['string'],
            'isolir_action' => ['required', 'string', 'in:change_profile,disabled_secret'],
            'isolir_profile' => ['required_if:isolir_action,change_profile', 'string'],
        ];
    }
}
