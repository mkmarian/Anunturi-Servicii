<x-guest-layout>
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Bun venit înapoi!</h1>
    <p class="text-sm text-gray-500 mb-6">Autentifică-te în contul tău MeseriiRo</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        @endif

        <div>
            <x-input-label for="email" :value="__('Adresa email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" :value="__('Parola')" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                        Ai uitat parola?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">Ține-mă minte</label>
        </div>

        <x-primary-button class="w-full justify-center py-3 text-base">
            Autentificare
        </x-primary-button>

        <p class="text-center text-sm text-gray-500 pt-2">
            Nu ai cont?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition">Înregistrare gratuită</a>
        </p>
    </form>
</x-guest-layout>
