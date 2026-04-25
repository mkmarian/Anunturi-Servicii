<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informații cont</h2>
        <p class="mt-1 text-sm text-gray-600">Actualizează datele contului tău.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5"
          enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Avatar --}}
        <div class="flex items-center gap-5">
            @if($user->profile?->avatar_path)
                <img src="{{ asset('uploads/' . $user->profile->avatar_path) }}"
                     class="w-16 h-16 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600 flex-shrink-0">
                    {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <x-input-label for="avatar" :value="__('Fotografie profil')" />
                <input id="avatar" name="avatar" type="file" accept="image/*"
                       class="mt-1 block text-sm text-gray-600">
                <x-input-error :messages="$errors->get('avatar')" class="mt-1" />
            </div>
        </div>

        {{-- Nume + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" value="Nume complet *" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                    :value="old('name', $user->name)" required autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="Email *" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="text-sm mt-1 text-amber-600">
                        Email neverificat.
                        <button form="send-verification" class="underline hover:text-amber-800">
                            Retrimite emailul de verificare.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 text-sm text-green-600">Link trimis!</p>
                    @endif
                @endif
            </div>
        </div>

        {{-- Telefon --}}
        <div class="max-w-xs">
            <x-input-label for="phone" value="Telefon" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full"
                :value="old('phone', $user->phone)" placeholder="07xx xxx xxx" />
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        {{-- Separator info publica --}}
        <hr class="border-gray-200">
        <p class="text-sm text-gray-500 font-medium">Informații publice (vizibile pe anunțuri)</p>

        {{-- Nume public + Firma --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="public_name" value="Nume afișat public" />
                <x-text-input id="public_name" name="public_name" class="mt-1 block w-full"
                    :value="old('public_name', $user->profile?->public_name)" placeholder="ex: Ion Popescu" />
                <x-input-error :messages="$errors->get('public_name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="company_name" value="Numele firmei (opțional)" />
                <x-text-input id="company_name" name="company_name" class="mt-1 block w-full"
                    :value="old('company_name', $user->profile?->company_name)" />
                <x-input-error :messages="$errors->get('company_name')" class="mt-1" />
            </div>
        </div>

        {{-- Judet + Localitate --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="county_id" value="Județ" />
                <select id="county_id" name="county_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">— Alege județul —</option>
                    @foreach($counties as $county)
                        <option value="{{ $county->id }}"
                            {{ old('county_id', $user->profile?->county_id) == $county->id ? 'selected' : '' }}>
                            {{ $county->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('county_id')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="city_id" value="Localitate" />
                <select id="city_id" name="city_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">— Alege localitatea —</option>
                    @if($user->profile?->city)
                        <option value="{{ $user->profile->city_id }}" selected>
                            {{ $user->profile->city->name }}
                        </option>
                    @endif
                </select>
                <x-input-error :messages="$errors->get('city_id')" class="mt-1" />
            </div>
        </div>

        {{-- Despre --}}
        <div>
            <x-input-label for="bio" value="Despre tine / firmă" />
            <textarea id="bio" name="bio" rows="4" maxlength="1000"
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                      placeholder="Câteva cuvinte despre experiența și serviciile tale...">{{ old('bio', $user->profile?->bio) }}</textarea>
            <x-input-error :messages="$errors->get('bio')" class="mt-1" />
        </div>

        {{-- Website --}}
        <div class="max-w-sm">
            <x-input-label for="website" value="Website (opțional)" />
            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                :value="old('website', $user->profile?->website)" placeholder="https://..." />
            <x-input-error :messages="$errors->get('website')" class="mt-1" />
        </div>

        {{-- Firma PF/PJ --}}
        @if(auth()->user()->isCraftsman())
        <div class="flex items-center gap-2">
            <input type="hidden" name="is_business" value="0">
            <input type="checkbox" id="is_business" name="is_business" value="1"
                   {{ old('is_business', $user->profile?->is_business) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <x-input-label for="is_business" value="Activez ca persoană juridică (firmă)" class="cursor-pointer" />
        </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Salvează modificările</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-green-600">Salvat!</p>
            @endif
        </div>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const countySelect = document.getElementById('county_id');
        const citySelect   = document.getElementById('city_id');
        const selectedCity = {{ $user->profile?->city_id ?? 'null' }};

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
        if (countySelect.value) loadCities(countySelect.value, selectedCity);
    });
    </script>
</section>
