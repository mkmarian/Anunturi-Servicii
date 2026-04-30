<x-guest-layout>
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Ai uitat parola?</h1>
    <p class="text-sm text-gray-500 mb-6">Îți trimitem un link de resetare pe email.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Adresa email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3 text-base">
            Trimite link de resetare
        </x-primary-button>
    </form>
</x-guest-layout>
