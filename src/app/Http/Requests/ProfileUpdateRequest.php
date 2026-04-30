<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'phone'        => ['nullable', 'string', 'max:20'],
            'public_name'  => ['nullable', 'string', 'max:120'],
            'company_name' => ['nullable', 'string', 'max:160'],
            'bio'          => ['nullable', 'string', 'max:1000'],
            'county_id'    => ['nullable', 'integer', 'exists:counties,id'],
            'city_id'      => ['nullable', 'integer', 'exists:cities,id'],
            'website'      => ['nullable', 'url', 'max:255'],
            'avatar'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([]);
    }
}
