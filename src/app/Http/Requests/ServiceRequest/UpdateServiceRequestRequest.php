<?php

namespace App\Http\Requests\ServiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sr = $this->route('cerere');
        return $this->user()?->id === $sr?->user_id;
    }

    public function rules(): array
    {
        return (new StoreServiceRequestRequest)->rules();
    }

    public function messages(): array
    {
        return (new StoreServiceRequestRequest)->messages();
    }
}
