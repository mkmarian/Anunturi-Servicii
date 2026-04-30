<form method="POST" action="{{ route('craftsman-application.store') }}" class="space-y-5">
    @csrf

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
            Ce servicii dorești să oferi? *
        </label>
        <select name="service_category_id" required
                class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
            <option value="">— Selectează categoria —</option>
            @foreach($categories as $category)
                @if($category->children->isEmpty())
                    <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->icon }} {{ $category->name }}
                    </option>
                @else
                    <optgroup label="{{ $category->icon }} {{ $category->name }}">
                        @foreach($category->children as $child)
                            <option value="{{ $child->id }}" {{ old('service_category_id') == $child->id ? 'selected' : '' }}>
                                {{ $child->icon }} {{ $child->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endif
            @endforeach
        </select>
        @error('service_category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
            Ani de experiență *
        </label>
        <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}"
               min="0" max="50" required
               class="w-32 border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">
        @error('experience_years')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">
            Descrie activitatea ta *
        </label>
        <textarea name="description" rows="4" required minlength="20" maxlength="2000"
                  placeholder="Prezintă-te pe scurt: ce servicii oferi, experiența ta, zona de lucru, etc."
                  class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm resize-y focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400">{{ old('description') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        <p class="text-xs text-gray-400 mt-1">Minim 20 de caractere. Descrierea va fi văzută de echipa noastră.</p>
    </div>

    <div class="pt-1">
        <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            Trimite cererea →
        </button>
    </div>
</form>
