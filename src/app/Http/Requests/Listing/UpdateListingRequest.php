<?php

namespace App\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $listing = $this->route('listing');
        return $this->user()?->id === $listing?->user_id;
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:180'],
            'category_id'       => ['required', 'integer', 'exists:service_categories,id'],
            'county_id'         => ['required', 'integer', 'exists:counties,id'],
            'city_id'           => ['required', 'integer', 'exists:cities,id'],
            'short_description' => ['nullable', 'string', 'max:320'],
            'description'       => ['required', 'string', 'max:10000'],
            'price_type'        => ['nullable', 'in:negotiable,fixed_price,from_price,free,on_request'],
            'price_from'        => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'price_to'          => ['nullable', 'numeric', 'min:0', 'max:999999', 'gte:price_from'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'show_phone'        => ['boolean'],
            'images'            => ['nullable', 'array', 'max:8'],
            'images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images'     => ['nullable', 'array'],
            'remove_images.*'   => ['integer', 'exists:listing_images,id'],
        ];
    }

    public function messages(): array
    {
        return (new StoreListingRequest)->messages();
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'show_phone' => $this->boolean('show_phone'),
        ]);
    }
}
