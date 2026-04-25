{{--
    Formular partajat de create si edit.
    Variabile disponibile: $listing (optional, pentru edit), $categories, $counties
--}}

@php $editing = isset($listing); @endphp

{{-- Titlu --}}
<div>
    <x-input-label for="title" :value="__('Titlu anunt *')" />
    <x-text-input id="title" name="title" class="block mt-1 w-full"
        value="{{ old('title', $listing->title ?? '') }}" required maxlength="180" />
    <x-input-error :messages="$errors->get('title')" class="mt-1" />
</div>

{{-- Categorie --}}
<div class="mt-4">
    <x-input-label for="category_id" :value="__('Categorie *')" />
    <select id="category_id" name="category_id" required
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">— Alege categoria —</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $listing->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @foreach($cat->children as $child)
                <option value="{{ $child->id }}" {{ old('category_id', $listing->category_id ?? '') == $child->id ? 'selected' : '' }}>
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
                <option value="{{ $county->id }}" {{ old('county_id', $listing->county_id ?? '') == $county->id ? 'selected' : '' }}>
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
            @if($editing && $listing->city)
                <option value="{{ $listing->city_id }}" selected>{{ $listing->city->name }}</option>
            @endif
        </select>
        <x-input-error :messages="$errors->get('city_id')" class="mt-1" />
    </div>
</div>

{{-- Descriere scurta --}}
<div class="mt-4">
    <x-input-label for="short_description" :value="__('Descriere scurta (optional, max 320 caractere)')" />
    <x-text-input id="short_description" name="short_description" class="block mt-1 w-full"
        value="{{ old('short_description', $listing->short_description ?? '') }}" maxlength="320" />
    <x-input-error :messages="$errors->get('short_description')" class="mt-1" />
</div>

{{-- Descriere completa --}}
<div class="mt-4">
    <x-input-label for="description" :value="__('Descriere completa *')" />
    <textarea id="description" name="description" rows="8" required maxlength="10000"
              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $listing->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-1" />
</div>

{{-- Pret --}}
<div class="mt-4">
    <x-input-label :value="__('Pret')" />
    <div class="mt-1 flex flex-wrap gap-3">
        @foreach(['negotiable' => 'Negociabil', 'fixed_price' => 'Pret fix', 'from_price' => 'Incepand de la', 'free' => 'Gratuit', 'on_request' => 'La cerere'] as $val => $label)
            <label class="flex items-center gap-1 cursor-pointer">
                <input type="radio" name="price_type" value="{{ $val }}"
                       {{ old('price_type', $listing->price_type ?? '') === $val ? 'checked' : '' }}
                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                <span class="text-sm text-gray-700">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="mt-3 grid grid-cols-2 gap-4" id="price-range">
    <div>
        <x-input-label for="price_from" :value="__('Pret de la (RON)')" />
        <x-text-input id="price_from" name="price_from" type="number" min="0" step="1"
            class="block mt-1 w-full"
            value="{{ old('price_from', $listing->price_from ?? '') }}" />
        <x-input-error :messages="$errors->get('price_from')" class="mt-1" />
    </div>
    <div>
        <x-input-label for="price_to" :value="__('Pret pana la (RON)')" />
        <x-text-input id="price_to" name="price_to" type="number" min="0" step="1"
            class="block mt-1 w-full"
            value="{{ old('price_to', $listing->price_to ?? '') }}" />
        <x-input-error :messages="$errors->get('price_to')" class="mt-1" />
    </div>
</div>

{{-- Telefon --}}
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
    <div>
        <x-input-label for="phone" :value="__('Telefon contact (optional)')" />
        <x-text-input id="phone" name="phone" type="tel" class="block mt-1 w-full"
            value="{{ old('phone', $listing->phone ?? '') }}" maxlength="30" />
        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
    </div>
    <div class="flex items-center gap-2 pb-1">
        <input id="show_phone" name="show_phone" type="checkbox" value="1"
               {{ old('show_phone', $listing->show_phone ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
        <label for="show_phone" class="text-sm text-gray-700">Afiseaza telefonul pe anunt</label>
    </div>
</div>

{{-- Imagini --}}
<div class="mt-6">
    <x-input-label :value="__('Imagini (max 8, JPG/PNG/WebP, max 5MB fiecare)')" />

    @if($editing && $listing->images->isNotEmpty())
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach($listing->images as $img)
                <div class="relative">
                    <img src="{{ Storage::url($img->path) }}" class="w-24 h-24 object-cover rounded border">
                    <label class="absolute top-0 right-0 bg-red-600 text-white rounded-bl px-1 cursor-pointer text-xs">
                        <input type="checkbox" name="remove_images[]" value="{{ $img->id }}" class="sr-only" />
                        ✕
                    </label>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-1">Bifeaza X pe imaginile pe care vrei sa le stergi.</p>
    @endif

    <input type="file" id="images" name="images[]" multiple accept="image/*"
           class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
    <x-input-error :messages="$errors->get('images')" class="mt-1" />
    <x-input-error :messages="$errors->get('images.*')" class="mt-1" />
</div>

{{-- Script: incarca orasele dynamicaly la schimbarea judetului --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countySelect = document.getElementById('county_id');
    const citySelect   = document.getElementById('city_id');
    const selectedCity = {{ $listing->city_id ?? 'null' }};

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

    // La incarcare pagina, daca e selectat un judet, incarca orasele
    if (countySelect.value) {
        loadCities(countySelect.value, selectedCity);
    }
});
</script>
