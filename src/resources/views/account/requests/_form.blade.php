{{--
    Formular partajat create + edit pentru cereri servicii.
    Variabile: $cerere (optional, edit), $categories, $counties
--}}

@php $editing = isset($cerere); @endphp

{{-- Titlu --}}
<div>
    <x-input-label for="title" :value="__('Ce ai nevoie? *')" />
    <x-text-input id="title" name="title" class="block mt-1 w-full"
        value="{{ old('title', $cerere->title ?? '') }}" required maxlength="180"
        placeholder="ex: Instalare instalatie electrica apartament 3 camere" />
    <x-input-error :messages="$errors->get('title')" class="mt-1" />
</div>

{{-- Categorie --}}
<div class="mt-4">
    <x-input-label for="category_id" :value="__('Categoria serviciului *')" />
    <select id="category_id" name="category_id" required
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">— Alege categoria —</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $cerere->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @foreach($cat->children as $child)
                <option value="{{ $child->id }}" {{ old('category_id', $cerere->category_id ?? '') == $child->id ? 'selected' : '' }}>
                    &nbsp;&nbsp;{{ $child->name }}
                </option>
            @endforeach
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
</div>

{{-- Judet + Localitate --}}
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="county_id" :value="__('Judet *')" />
        <select id="county_id" name="county_id" required
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">— Alege judetul —</option>
            @foreach($counties as $county)
                <option value="{{ $county->id }}" {{ old('county_id', $cerere->county_id ?? '') == $county->id ? 'selected' : '' }}>
                    {{ $county->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('county_id')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="city_id" :value="__('Localitate *')" />
        <select id="city_id" name="city_id" required
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">— Alege localitatea —</option>
            @if($editing && $cerere->city)
                <option value="{{ $cerere->city_id }}" selected>{{ $cerere->city->name }}</option>
            @endif
        </select>
        <x-input-error :messages="$errors->get('city_id')" class="mt-1" />
    </div>
</div>

{{-- Descriere --}}
<div class="mt-4">
    <x-input-label for="description" :value="__('Descriere detaliata *')" />
    <textarea id="description" name="description" rows="7" required maxlength="5000"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              placeholder="Descrie cat mai detaliat ce ai nevoie: suprafata, materiale, conditii specifice...">{{ old('description', $cerere->description ?? '') }}</textarea>
    <p class="text-xs text-gray-500 mt-1">Cu cat esti mai specific, cu atat vei primi raspunsuri mai relevante.</p>
    <x-input-error :messages="$errors->get('description')" class="mt-1" />
</div>

{{-- Budget --}}
<div class="mt-4">
    <x-input-label :value="__('Buget estimativ')" />
    <div class="mt-1 flex flex-wrap gap-3">
        @foreach(['negotiable' => 'Negociabil', 'fixed' => 'Buget fix', 'max_budget' => 'Maxim X lei', 'unknown' => 'Nu stiu inca'] as $val => $label)
            <label class="flex items-center gap-1 cursor-pointer">
                <input type="radio" name="budget_type" value="{{ $val }}"
                       {{ old('budget_type', $cerere->budget_type ?? '') === $val ? 'checked' : '' }}
                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="mt-3 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="budget_from" :value="__('Buget de la (RON)')" />
        <x-text-input id="budget_from" name="budget_from" type="number" min="0" step="1"
            class="block mt-1 w-full"
            value="{{ old('budget_from', $cerere->budget_from ?? '') }}" />
        <x-input-error :messages="$errors->get('budget_from')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="budget_to" :value="__('Buget pana la (RON)')" />
        <x-text-input id="budget_to" name="budget_to" type="number" min="0" step="1"
            class="block mt-1 w-full"
            value="{{ old('budget_to', $cerere->budget_to ?? '') }}" />
        <x-input-error :messages="$errors->get('budget_to')" class="mt-1" />
    </div>
</div>

{{-- Data dorita --}}
<div class="mt-4 max-w-xs">
    <x-input-label for="desired_date" :value="__('Data dorita (optional, format zz-ll-aaaa)')" />
    <x-text-input id="desired_date" name="desired_date" class="block mt-1 w-full"
        placeholder="ex: 25-05-2026"
        value="{{ old('desired_date', isset($cerere) && $cerere->desired_date ? $cerere->desired_date->format('d-m-Y') : '') }}" />
    <x-input-error :messages="$errors->get('desired_date')" class="mt-1" />
</div>

{{-- Script AJAX orase --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countySelect = document.getElementById('county_id');
    const citySelect   = document.getElementById('city_id');
    const selectedCity = {{ isset($cerere) ? ($cerere->city_id ?? 'null') : 'null' }};

    function loadCities(countyId, preselectId) {
        if (!countyId) {
            citySelect.innerHTML = '<option value="">— Alege localitatea —</option>';
            return;
        }
        fetch('/api/counties/' + countyId + '/cities')
            .then(r => r.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">— Alege localitatea —</option>';
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name;
                    if (preselectId && c.id == preselectId) opt.selected = true;
                    citySelect.appendChild(opt);
                });
            });
    }

    countySelect.addEventListener('change', () => loadCities(countySelect.value, null));

    if (countySelect.value) {
        loadCities(countySelect.value, selectedCity);
    }
});
</script>
