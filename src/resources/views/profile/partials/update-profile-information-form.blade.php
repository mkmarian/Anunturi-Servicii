<form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5"
          enctype="multipart/form-data">
        @csrf
        @method('patch')

        <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden" onchange="previewAndSubmit(this)">

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
    function previewAndSubmit(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (initials) initials.classList.add('hidden');
            setTimeout(() => input.closest('form').submit(), 100);
        };
        reader.readAsDataURL(input.files[0]);
    }
    </script>
