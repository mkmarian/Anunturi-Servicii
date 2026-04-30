<?php

namespace App\Http\Controllers;

use App\Domain\Accounts\Models\CraftsmanApplication;
use App\Domain\Shared\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CraftsmanApplicationController extends Controller
{
    /**
     * Trimite cererea de a deveni meserias.
     * Accesibil doar de clienti fara cerere activa.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Doar clientii pot aplica
        if (! $user->isCustomer()) {
            return back()->with('error', 'Doar clienții pot trimite o cerere de meseriaș.');
        }

        // Nu poate aplica daca are deja o cerere in asteptare sau aprobata
        $existing = $user->craftsmanApplications()
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Ai deja o cerere ' . ($existing->isPending() ? 'în așteptare' : 'aprobată') . '.');
        }

        $validated = $request->validate([
            'service_category_id' => ['required', 'exists:service_categories,id'],
            'experience_years'    => ['required', 'integer', 'min:0', 'max:50'],
            'description'         => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'service_category_id.required' => 'Selectează categoria de servicii.',
            'service_category_id.exists'   => 'Categoria selectată nu există.',
            'experience_years.required'    => 'Introdu anii de experiență.',
            'experience_years.min'         => 'Experiența nu poate fi negativă.',
            'experience_years.max'         => 'Valoarea maximă este 50 de ani.',
            'description.required'         => 'Descrie activitatea ta.',
            'description.min'              => 'Descrierea trebuie să aibă cel puțin 20 de caractere.',
            'description.max'              => 'Descrierea nu poate depăși 2000 de caractere.',
        ]);

        $user->craftsmanApplications()->create($validated);

        return back()->with('success', 'Cererea ta a fost trimisă! Vei fi notificat după revizuire.');
    }
}
