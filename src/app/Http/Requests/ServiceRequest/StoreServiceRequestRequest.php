<?php

namespace App\Http\Requests\ServiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:180'],
            'category_id'  => ['required', 'integer', 'exists:service_categories,id'],
            'county_id'    => ['required', 'integer', 'exists:counties,id'],
            'city_id'      => ['required', 'integer', 'exists:cities,id'],
            'description'  => ['required', 'string', 'max:5000'],
            'budget_type'  => ['nullable', 'in:fixed,max_budget,negotiable,unknown'],
            'budget_from'  => ['nullable', 'numeric', 'min:0', 'max:999999'],
            'budget_to'    => ['nullable', 'numeric', 'min:0', 'max:999999', 'gte:budget_from'],
            'desired_date' => ['nullable', 'romanian_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Titlul este obligatoriu.',
            'title.max'            => 'Titlul nu poate depasi 180 de caractere.',
            'category_id.required' => 'Selecteaza o categorie.',
            'category_id.exists'   => 'Categoria selectata nu exista.',
            'county_id.required'   => 'Selecteaza judetul.',
            'city_id.required'     => 'Selecteaza localitatea.',
            'description.required' => 'Descrierea este obligatorie.',
            'budget_to.gte'        => 'Bugetul maxim trebuie sa fie mai mare decat bugetul minim.',
            'desired_date'         => 'Formatul datei trebuie sa fie zz-ll-aaaa.',
        ];
    }
}
