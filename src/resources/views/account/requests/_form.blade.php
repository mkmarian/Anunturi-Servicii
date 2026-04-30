{{--
    Formular partajat create + edit pentru cereri servicii.
    Variabile: $cerere (optional, edit), $categories, $counties
--}}

@php $editing = isset($cerere); @endphp

{{-- Titlu --}}
<div>
    <x-input-label for="title" :value="__('De ce ai nevoie? *')" />
    <x-text-input id="title" name="title" class="block mt-1 w-full"
        value="{{ old('title', $cerere->title ?? '') }}" required maxlength="180"
        placeholder="Spune de ce serviciu ai nevoie" />
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
                {{ $cat->icon }} {{ $cat->name }}
            </option>
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
    <x-input-error :messages="$errors->get('description')" class="mt-1" />
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
