<x-admin-layout title="Editează: {{ $category->name }}">

    <div class="mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
            ← Înapoi la categorii
        </a>
    </div>

    <div class="max-w-xl bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-800 mb-5">Editează categoria</h2>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Nume *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Icon (emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="ex: 🔧"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Categorie părinte</label>
                <select name="parent_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 bg-white">
                    <option value="">— Niciuna (categorie principală) —</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->icon }} {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Slug (generat automat)</label>
                <p class="text-sm text-gray-500 font-mono bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">{{ $category->slug }}</p>
                <p class="text-xs text-gray-400 mt-1">Se regenerează automat dacă schimbi numele.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Ordine sortare</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                       class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
                <p class="text-xs text-gray-400 mt-1">Număr mic = apare primul</p>
                @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="text-sm text-gray-700">Categorie activă (vizibilă pe site)</label>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Salvează modificările
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-2 text-sm text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    Anulează
                </a>
            </div>
        </form>
    </div>

    {{-- Statistici rapide --}}
    <div class="max-w-xl mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
        📋 Această categorie are <strong>{{ $category->listings_count ?? $category->listings()->count() }}</strong> anunțuri asociate.
        @if(($category->listings_count ?? $category->listings()->count()) > 0)
            Nu poate fi ștearsă cât timp are anunțuri.
        @endif
    </div>

</x-admin-layout>
